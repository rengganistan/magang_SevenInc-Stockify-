<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class StockTransaction extends Model
{
    protected $fillable = [
        'product_id',
        'supplier_id',
        'user_id',
        'type',
        'quantity',
        'date',
        'status',
        'notes'
    ];

    protected static function booted(): void
    {
        $bust = function () {
            Cache::forget('nav_low_stock');
            Cache::forget('nav_pending_count');
            Cache::forget('nav_recent_transactions');
        };
        static::created($bust);
        static::updated($bust);
        static::deleted($bust);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getIncomingTransactions()
{
    return $this->repository->incoming();
}

public function getOutgoingTransactions()
{
    return $this->repository->outgoing();
}
}
