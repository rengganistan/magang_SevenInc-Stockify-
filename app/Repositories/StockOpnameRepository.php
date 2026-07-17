<?php

namespace App\Repositories;

use App\Models\StockOpname;
use App\Models\StockOpnameItem;

class StockOpnameRepository
{
    public function getAll()
    {
        return StockOpname::with('user')
            ->latest()
            ->get();
    }

    public function findById($id)
    {
        return StockOpname::with(['user', 'items.product'])
            ->findOrFail($id);
    }

    public function create(array $data): StockOpname
    {
        return StockOpname::create($data);
    }

    public function createItem(array $data): StockOpnameItem
    {
        return StockOpnameItem::create($data);
    }

    public function updateStatus($id, string $status): void
    {
        StockOpname::findOrFail($id)->update(['status' => $status]);
    }

    public function delete($id): void
    {
        StockOpname::findOrFail($id)->delete();
    }
}
