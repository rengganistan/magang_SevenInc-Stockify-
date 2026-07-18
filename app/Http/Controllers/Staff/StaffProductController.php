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
        $search   = $request->input('search');
        $products = $this->service->getProducts($search);
        return view('staff.products.index', compact('products', 'search'));
    }

    public function show(int $id): View
    {
        $product    = $this->service->getProductById($id);
        $attributes = $product->attributes;
        return view('staff.products.show', compact('product', 'attributes'));
    }
}
