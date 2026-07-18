<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Product;
use App\Services\StockOpnameService;
use Illuminate\Http\Request;

class ManagerStockOpnameController extends Controller
{
    protected StockOpnameService $service;

    public function __construct(StockOpnameService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $opnames = $this->service->getAll();
        return view('manager.stock-opname.index', compact('opnames'));
    }

    public function create()
    {
        $products = Product::with('category')->orderBy('nama')->get();
        return view('manager.stock-opname.create', compact('products'));
    }

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

        return redirect()->route('manager.stock-opname.index')
            ->with('success', 'Stock opname berhasil disimpan sebagai draft.');
    }

    public function show($id)
    {
        $opname = $this->service->findById($id);
        return view('manager.stock-opname.show', compact('opname'));
    }

    public function selesaikan($id)
    {
        $this->service->selesaikan($id);

        ActivityLog::record('Selesaikan Stock Opname', 'Stock Opname', 'Opname #' . $id);

        return redirect()->route('manager.stock-opname.show', $id)
            ->with('success', 'Stock opname berhasil diselesaikan. Stok produk telah diperbarui.');
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return redirect()->route('manager.stock-opname.index')
            ->with('success', 'Draft opname berhasil dihapus.');
    }
}
