<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model',
        'model_name',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | STATIC HELPER
    | Panggil dari controller: ActivityLog::record('Tambah Produk', 'Produk', $nama)
    |--------------------------------------------------------------------------
    */
    public static function record(
        string $action,
        string $model = null,
        string $modelName = null,
        string $keterangan = null
    ): void {
        static::create([
            'user_id'    => Auth::id(),
            'action'     => $action,
            'model'      => $model,
            'model_name' => $modelName,
            'keterangan' => $keterangan,
        ]);

        // Bust cache navbar notifikasi
        \Illuminate\Support\Facades\Cache::forget('nav_notifications');
    }
}
