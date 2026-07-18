<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProductAttributeService;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller
{
    protected ProductAttributeService $service;

    public function __construct(ProductAttributeService $service)
    {
        $this->service = $service;
    }

    /*
    |--------------------------------------------------------------------------
    | STORE — tambah atribut ke produk
    |--------------------------------------------------------------------------
    */
    public function store(Request $request, $productId)
    {
        $request->validate([
            'name'  => 'required|string|max:100',
            'value' => 'required|string|max:255',
        ]);

        $this->service->create([
            'product_id' => $productId,
            'name'       => $request->name,
            'value'      => $request->value,
        ]);

        return redirect()
            ->route('products.edit', $productId)
            ->with('success_attr', 'Atribut berhasil ditambahkan.');
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY — hapus atribut
    |--------------------------------------------------------------------------
    */
    public function destroy($productId, $id)
    {
        $this->service->delete($id);

        return redirect()
            ->route('products.edit', $productId)
            ->with('success_attr', 'Atribut berhasil dihapus.');
    }
}
