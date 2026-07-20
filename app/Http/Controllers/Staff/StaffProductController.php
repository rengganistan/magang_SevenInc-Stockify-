<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProductService;
use Illuminate\View\View;

class StaffProductController extends Controller
{
    protected ProductService $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): View
    {
        $search     = $request->input('search');
        $stokFilter = $request->input('stok');
        $products   = $this->service->getProducts($search, $stokFilter);

        $totalProducts = \App\Models\Product::count();
        $totalKategori = \App\Models\Product::distinct('category_id')->count('category_id');
        $stokMenipis   = \App\Models\Product::whereColumn('stok', '<=', 'stok_minimum')->where('stok', '>', 0)->count();
        $stokHabis     = \App\Models\Product::where('stok', 0)->count();

        return view('staff.products.index', compact('products', 'search', 'stokFilter', 'totalProducts', 'totalKategori', 'stokMenipis', 'stokHabis'));
    }

    public function show(int $id): View
    {
        $product    = $this->service->getProductById($id);
        $attributes = $product->attributes;
        return view('staff.products.show', compact('product', 'attributes'));
    }
}
