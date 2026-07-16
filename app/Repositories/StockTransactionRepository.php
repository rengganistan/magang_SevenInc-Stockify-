<?php

namespace App\Repositories;

use App\Models\StockTransaction;

class StockTransactionRepository
{
    public function getAll()
    {
        return StockTransaction::with([
                'product',
                'user'
            ])
            ->latest()
            ->get();
    }

    public function findById($id)
    {
        return StockTransaction::findOrFail($id);
    }

    public function create(array $data)
    {
        return StockTransaction::create($data);
    }

    public function update($id, array $data)
    {
        $transaction = StockTransaction::findOrFail($id);

        $transaction->update($data);

        return $transaction;
    }

    public function delete($id)
    {
        return StockTransaction::destroy($id);
    }
}
