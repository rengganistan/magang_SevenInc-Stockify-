<?php

namespace App\Repositories;

use App\Models\ProductAttribute;

class ProductAttributeRepository
{
    public function getByProduct($productId)
    {
        return ProductAttribute::where('product_id', $productId)->get();
    }

    public function create(array $data): ProductAttribute
    {
        return ProductAttribute::create($data);
    }

    public function delete($id): void
    {
        ProductAttribute::findOrFail($id)->delete();
    }
}
