<?php

namespace App\Services;

use App\Repositories\ProductAttributeRepository;

class ProductAttributeService
{
    protected ProductAttributeRepository $repository;

    public function __construct(ProductAttributeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getByProduct($productId)
    {
        return $this->repository->getByProduct($productId);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function delete($id): void
    {
        $this->repository->delete($id);
    }
}
