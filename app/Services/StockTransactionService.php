<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Repositories\StockTransactionRepository;

class StockTransactionService
{
    protected StockTransactionRepository $repository;

    public function __construct(
        StockTransactionRepository $repository
    )
    {
        $this->repository = $repository;
    }

    public function getTransactions()
    {
        return $this->repository->getAll();
    }

    public function getTransactionById($id)
    {
        return $this->repository->findById($id);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE TRANSACTION
    |--------------------------------------------------------------------------
    */

    public function createTransaction(array $data)
    {
        DB::transaction(function () use ($data) {

            $product = Product::findOrFail(
                $data['product_id']
            );

            /*
            |--------------------------------------------------------------------------
            | BARANG MASUK
            |--------------------------------------------------------------------------
            */

            if ($data['type'] == 'Masuk') {

                $product->stok += $data['quantity'];

            }

            /*
            |--------------------------------------------------------------------------
            | BARANG KELUAR
            |--------------------------------------------------------------------------
            */

            if ($data['type'] == 'Keluar') {

                if ($product->stok < $data['quantity']) {

                    abort(
                        422,
                        'Stok produk tidak mencukupi.'
                    );

                }

                $product->stok -= $data['quantity'];

            }

            $product->save();

            $this->repository->create($data);

        });
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function updateTransaction($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function deleteTransaction($id)
    {
        return $this->repository->delete($id);
    }
}
