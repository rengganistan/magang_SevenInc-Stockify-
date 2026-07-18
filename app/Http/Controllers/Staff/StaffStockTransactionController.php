<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\StockTransactionService;

class StaffStockTransactionController extends Controller
{
    protected StockTransactionService $service;

    public function __construct(StockTransactionService $service)
    {
        $this->service = $service;
    }

    /*
    |----------------------------------------------------------------------
    | LIST — semua transaksi masuk (bukan hanya milik sendiri)
    |----------------------------------------------------------------------
    */
    public function incoming()
    {
        $transactions = $this->service->getIncomingTransactions();
        return view('staff.stock-transactions.incoming', compact('transactions'));
    }

    public function outgoing()
    {
        $transactions = $this->service->getOutgoingTransactions();
        return view('staff.stock-transactions.outgoing', compact('transactions'));
    }

    /*
    |----------------------------------------------------------------------
    | CREATE — form transaksi baru, default status Pending
    |----------------------------------------------------------------------
    */
    public function create()
    {
        $products  = Product::orderBy('nama')->get();
        $suppliers = Supplier::orderBy('nama')->get();
        $type      = request('type', 'Masuk');
        return view('staff.stock-transactions.create', compact('products', 'suppliers', 'type'));
    }

    /*
    |----------------------------------------------------------------------
    | STORE — simpan transaksi dengan status Pending
    | Stok belum berubah sampai dikonfirmasi
    |----------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id'  => 'required|exists:products,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'type'        => 'required|in:Masuk,Keluar',
            'quantity'    => 'required|integer|min:1',
            'date'        => 'required|date',
            'notes'       => 'nullable',
        ]);

        // Status selalu Pending saat staff membuat transaksi baru
        $validated['user_id'] = Auth::id();
        $validated['status']  = 'Pending';

        // Simpan transaksi TANPA mengubah stok dulu
        StockTransaction::create($validated);

        $product = Product::find($validated['product_id']);
        $action  = $validated['type'] === 'Masuk' ? 'Catat Barang Masuk (Pending)' : 'Catat Barang Keluar (Pending)';
        ActivityLog::record($action, 'Transaksi', $product->nama ?? '-', 'Qty: ' . $validated['quantity']);

        $route = $validated['type'] === 'Masuk'
            ? 'staff.transactions.incoming'
            : 'staff.transactions.outgoing';

        return redirect()->route($route)
            ->with('success', 'Transaksi berhasil dicatat dengan status Pending. Konfirmasi untuk memperbarui stok.');
    }

    /*
    |----------------------------------------------------------------------
    | CONFIRM — konfirmasi transaksi Pending → update stok + ubah status
    |----------------------------------------------------------------------
    */
    public function confirm($id)
    {
        $tx = StockTransaction::with('product')->findOrFail($id);

        if ($tx->status !== 'Pending') {
            return back()->with('error', 'Transaksi ini sudah dikonfirmasi sebelumnya.');
        }

        $product = $tx->product;

        if ($tx->type === 'Masuk') {
            $product->stok += $tx->quantity;
            $newStatus = 'Diterima';
        } else {
            if ($product->stok < $tx->quantity) {
                return back()->with('error', 'Stok produk tidak mencukupi untuk dikonfirmasi.');
            }
            $product->stok -= $tx->quantity;
            $newStatus = 'Dikeluarkan';
        }

        $product->save();
        $tx->update(['status' => $newStatus]);

        ActivityLog::record(
            'Konfirmasi ' . ($tx->type === 'Masuk' ? 'Barang Masuk' : 'Barang Keluar'),
            'Transaksi',
            $product->nama ?? '-',
            'Qty: ' . $tx->quantity . ' | Status: ' . $newStatus
        );

        $route = $tx->type === 'Masuk'
            ? 'staff.transactions.incoming'
            : 'staff.transactions.outgoing';

        return redirect()->route($route)
            ->with('success', 'Transaksi berhasil dikonfirmasi. Stok produk telah diperbarui.');
    }
}
