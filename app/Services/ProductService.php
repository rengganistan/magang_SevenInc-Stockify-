<?php

namespace App\Services;

use App\Repositories\ProductRepository;

class ProductService
{

    protected ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository=$productRepository;
    }

    public function getProducts(?string $search = null, ?string $stokFilter = null)
    {
        return $this->productRepository->getAll($search, $stokFilter);
    }

    public function getProductById($id)
    {
        return $this->productRepository->findById($id);
    }

    public function createProduct(array $data)
    {
        return $this->productRepository->create($data);
    }

    public function updateProduct($id,array $data)
    {
        return $this->productRepository->update($id,$data);
    }

    public function deleteProduct($id)
    {
        return $this->productRepository->delete($id);
    }

}
