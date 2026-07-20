<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ManagerProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function show(int $id): View
    {
        $product    = $this->productService->getProductById($id);
        $attributes = $product->attributes;
        return view('manager.products.show', compact('product', 'attributes'));
    }

    public function index(Request $request): View
    {
        $search     = $request->input('search');
        $stokFilter = $request->input('stok');
        $products   = $this->productService->getProducts($search, $stokFilter);

        $totalProducts = \App\Models\Product::count();
        $totalKategori = \App\Models\Product::distinct('category_id')->count('category_id');
        $stokMenipis   = \App\Models\Product::whereColumn('stok', '<=', 'stok_minimum')->where('stok', '>', 0)->count();
        $stokHabis     = \App\Models\Product::where('stok', 0)->count();

        return view('manager.products.index', compact('products', 'search', 'stokFilter', 'totalProducts', 'totalKategori', 'stokMenipis', 'stokHabis'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('nama')->get();
        $suppliers  = Supplier::orderBy('nama')->get();
        return view('manager.products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode'         => 'required|unique:products,kode',
            'nama'         => 'required|string|max:255',
            'category_id'  => 'required|exists:categories,id',
            'supplier_id'  => 'required|exists:suppliers,id',
            'satuan'       => 'required|string|max:100',
            'stok'         => 'required|integer',
            'stok_minimum' => 'required|integer',
            'harga_beli'   => 'required|numeric',
            'harga_jual'   => 'required|numeric',
            'deskripsi'    => 'nullable|string',
            'gambar'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('products', 'public');
        }

        $this->productService->createProduct($validated);

        ActivityLog::record('Tambah Produk', 'Produk', $validated['nama']);

        return redirect()->route('manager.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $product    = $this->productService->getProductById($id);
        $categories = Category::orderBy('nama')->get();
        $suppliers  = Supplier::orderBy('nama')->get();
        $attributes = $product->attributes;
        return view('manager.products.edit', compact('product', 'categories', 'suppliers', 'attributes'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'kode'         => 'required|unique:products,kode,' . $id,
            'nama'         => 'required|string|max:255',
            'category_id'  => 'required|exists:categories,id',
            'supplier_id'  => 'required|exists:suppliers,id',
            'satuan'       => 'required|string|max:100',
            'stok'         => 'required|integer',
            'stok_minimum' => 'required|integer',
            'harga_beli'   => 'required|numeric',
            'harga_jual'   => 'required|numeric',
            'deskripsi'    => 'nullable|string',
            'gambar'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('products', 'public');
        }

        $this->productService->updateProduct($id, $validated);

        ActivityLog::record('Edit Produk', 'Produk', $validated['nama']);

        return redirect()->route('manager.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $product = $this->productService->getProductById($id);
        $nama    = $product->nama;

        $this->productService->deleteProduct($id);

        ActivityLog::record('Hapus Produk', 'Produk', $nama);

        return redirect()->route('manager.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
