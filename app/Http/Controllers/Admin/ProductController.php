<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\ProductExport;
use App\Imports\ProductImport;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request): View
    {
        $search   = $request->input('search');
        $stokFilter = $request->input('stok'); // 'menipis' | 'habis' | null
        $products = $this->productService->getProducts($search, $stokFilter);

        // Stat cards — query langsung biar akurat (bukan dari paginated)
        $totalProducts  = \App\Models\Product::count();
        $totalKategori  = \App\Models\Product::distinct('category_id')->count('category_id');
        $stokMenipis    = \App\Models\Product::whereColumn('stok', '<=', 'stok_minimum')->where('stok', '>', 0)->count();
        $stokHabis      = \App\Models\Product::where('stok', 0)->count();

        return view('products.index', compact('products', 'search', 'stokFilter', 'totalProducts', 'totalKategori', 'stokMenipis', 'stokHabis'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('nama')->get();
        $suppliers  = Supplier::orderBy('nama')->get();
        return view('products.create', compact('categories', 'suppliers'));
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

        $product = $this->productService->createProduct($validated);

        ActivityLog::record('Tambah Produk', 'Produk', $validated['nama']);

        return redirect()->route('products.edit', $product->id)
            ->with('success', 'Produk berhasil ditambahkan. Tambahkan atribut produk di bawah ini.');
    }

    public function edit(int $id): View
    {
        $product    = $this->productService->getProductById($id);
        $categories = Category::orderBy('nama')->get();
        $suppliers  = Supplier::orderBy('nama')->get();
        $attributes = $product->attributes;
        return view('products.edit', compact('product', 'categories', 'suppliers', 'attributes'));
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

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $product = $this->productService->getProductById($id);
        $nama    = $product->nama;

        $this->productService->deleteProduct($id);

        ActivityLog::record('Hapus Produk', 'Produk', $nama);

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT
    |--------------------------------------------------------------------------
    */
    public function export()
    {
        ActivityLog::record('Export Produk', 'Produk', 'Export Excel');

        return Excel::download(new ProductExport, 'produk-' . date('Y-m-d') . '.xlsx');
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORT
    |--------------------------------------------------------------------------
    */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        $import = new ProductImport;

        Excel::import($import, $request->file('file'));

        $errors = $import->getErrors();

        ActivityLog::record('Import Produk', 'Produk', count($errors) . ' error', 'File: ' . $request->file('file')->getClientOriginalName());

        if (!empty($errors)) {
            return redirect()->route('products.index')
                ->with('import_errors', $errors)
                ->with('success', 'Import selesai dengan beberapa kesalahan.');
        }

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diimport.');
    }
}
