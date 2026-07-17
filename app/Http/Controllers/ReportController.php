<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReportService;

class ReportController extends Controller
{
    protected ReportService $service;

    public function __construct(ReportService $service)
    {
        $this->service = $service;
    }

    /*
    |--------------------------------------------------------------------------
    | LAPORAN STOK
    |--------------------------------------------------------------------------
    */

    public function stock(Request $request)
    {
        $category = $request->category;

        $products = $this->service->stock($category);

        $categories = $this->service->categories();

        return view(
            'reports.stock',
            compact(
                'products',
                'categories'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LAPORAN TRANSAKSI
    |--------------------------------------------------------------------------
    */

    public function transaction(Request $request)
    {
        $transactions = $this->service->transaction(
            $request->type,
            $request->start_date,
            $request->end_date
        );

        return view(
            'reports.transaction',
            compact('transactions')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LAPORAN AKTIVITAS USER
    |--------------------------------------------------------------------------
    */

    public function activity()
    {
        $activities = $this->service->activity();

        return view(
            'reports.activity',
            compact('activities')
        );
    }
}
