<?php

namespace App\Services;

use App\Repositories\ReportRepository;

class ReportService
{
    protected ReportRepository $repository;

    public function __construct(ReportRepository $repository)
    {
        $this->repository = $repository;
    }

    /*
    |--------------------------------------------------------------------------
    | Laporan Stok
    |--------------------------------------------------------------------------
    */

    public function stock($category = null)
    {
        return $this->repository->stock($category);
    }

    /*
    |--------------------------------------------------------------------------
    | Data Kategori
    |--------------------------------------------------------------------------
    */

    public function categories()
    {
        return $this->repository->categories();
    }

    /*
    |--------------------------------------------------------------------------
    | Laporan Transaksi
    |--------------------------------------------------------------------------
    */

    public function transaction($type = null, $start = null, $end = null)
    {
        return $this->repository->transaction(
            $type,
            $start,
            $end
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Aktivitas User
    |--------------------------------------------------------------------------
    */

    public function activity($user = null, $start = null, $end = null)
    {
        return $this->repository->activity($user, $start, $end);
    }
}
