<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\StockTransactionService;

class ManagerStockTransactionController extends Controller
{
    protected StockTransactionService $service;

    public function __construct(StockTransactionService $service)
    {
        $this->service = $service;
    }

    public function incoming()
    {
        $transactions = $this->service->getIncomingTransactions();
        return view('manager.stock-transactions.incoming', compact('transactions'));
    }

    public function outgoing()
    {
        $transactions = $this->service->getOutgoingTransactions();
        return view('manager.stock-transactions.outgoing', compact('transactions'));
    }

    public function create()
    {
        $products  = Product::orderBy('nama')->get();
        $suppliers = Supplier::orderBy('nama')->get();
        $type      = request('type', 'Masuk');
        return view('manager.stock-transactions.create', compact('products', 'suppliers', 'type'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id'  => 'required|exists:products,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'type'        => 'required|in:Masuk,Keluar',
            'quantity'    => 'required|integer|min:1',
            'date'        => 'required|date',
            'status'      => 'required',
            'notes'       => 'nullable',
        ]);

        $validated['user_id'] = Auth::id();

        $this->service->createTransaction($validated);

        $product = Product::find($validated['product_id']);
        $action  = $validated['type'] === 'Masuk' ? 'Barang Masuk' : 'Barang Keluar';
        ActivityLog::record($action, 'Transaksi', $product->nama ?? '-', 'Qty: ' . $validated['quantity']);

        $route = $validated['type'] === 'Masuk'
            ? 'manager.transactions.incoming'
            : 'manager.transactions.outgoing';

        return redirect()->route($route)
            ->with('success', 'Transaksi berhasil disimpan.');
    }

    public function destroy($id)
    {
        $this->service->deleteTransaction($id);

        return redirect()->route('manager.transactions.incoming')
            ->with('success', 'Transaksi berhasil dihapus.');
    }
}
