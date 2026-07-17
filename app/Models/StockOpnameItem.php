<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpnameItem extends Model
{
    protected $fillable = [
        'stock_opname_id',
        'product_id',
        'stok_sistem',
        'stok_fisik',
        'selisih',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function stockOpname()
    {
        return $this->belongsTo(StockOpname::class);
    }
}
