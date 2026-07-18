<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ActivityLog;
use App\Services\StockOpnameService;
use Illuminate\Http\Request;

class StockOpnameController extends Controller
{
    protected StockOpnameService $service;

    public function __construct(StockOpnameService $service)
    {
        $this->service = $service;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX — daftar riwayat opname
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $opnames = $this->service->getAll();

        return view('stock-opname.index', compact('opnames'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE — form opname baru
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $products = Product::with('category')
            ->orderBy('nama')
            ->get();

        return view('stock-opname.create', compact('products'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE — simpan opname sebagai draft
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal'    => 'required|date',
            'catatan'    => 'nullable|string',
            'stok_fisik' => 'required|array',
        ]);

        $this->service->store(
            $request->only('tanggal', 'catatan'),
            $request->input('stok_fisik')
        );

        ActivityLog::record('Buat Stock Opname', 'Stock Opname', 'Tanggal: ' . $request->tanggal, $request->catatan);

        return redirect()
            ->route('stock-opname.index')
            ->with('success', 'Stock opname berhasil disimpan sebagai draft.');
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW — detail opname
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $opname = $this->service->findById($id);

        return view('stock-opname.show', compact('opname'));
    }

    /*
    |--------------------------------------------------------------------------
    | SELESAIKAN — terapkan penyesuaian stok
    |--------------------------------------------------------------------------
    */
    public function selesaikan($id)
    {
        $this->service->selesaikan($id);

        ActivityLog::record('Selesaikan Stock Opname', 'Stock Opname', 'Opname #' . $id);

        return redirect()
            ->route('stock-opname.show', $id)
            ->with('success', 'Stock opname berhasil diselesaikan. Stok produk telah diperbarui.');
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY — hapus draft opname
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $this->service->delete($id);

        return redirect()
            ->route('stock-opname.index')
            ->with('success', 'Draft opname berhasil dihapus.');
    }
}
