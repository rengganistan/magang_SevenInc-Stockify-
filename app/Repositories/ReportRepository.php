<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Category;
use App\Models\StockTransaction;
use App\Models\User;

class ReportRepository
{
    /*
    |--------------------------------------------------------------------------
    | LAPORAN STOK
    |--------------------------------------------------------------------------
    */

    public function stock($category = null)
    {
        return Product::with([
            'category',
            'supplier'
        ])
        ->when($category, function ($query) use ($category) {

            $query->where('category_id', $category);

        })
        ->orderBy('nama')
        ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | KATEGORI
    |--------------------------------------------------------------------------
    */

    public function categories()
    {
        return Category::orderBy('nama')->get();
    }

    /*
    |--------------------------------------------------------------------------
    | TRANSAKSI
    |--------------------------------------------------------------------------
    */

    public function transaction($type = null, $start = null, $end = null)
    {
        return StockTransaction::with([
            'product',
            'user'
        ])
        ->when($type, function ($q) use ($type) {

            $q->where('type', $type);

        })
        ->when($start, function ($q) use ($start) {

            $q->whereDate('date', '>=', $start);

        })
        ->when($end, function ($q) use ($end) {

            $q->whereDate('date', '<=', $end);

        })
        ->latest()
        ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | AKTIVITAS USER
    |--------------------------------------------------------------------------
    */

    public function activity()
    {
        return StockTransaction::with([
            'product',
            'user'
        ])
        ->latest()
        ->get();
    }
}
