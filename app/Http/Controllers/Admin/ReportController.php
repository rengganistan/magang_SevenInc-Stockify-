<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $start    = $request->start_date;
        $end      = $request->end_date;

        $products   = $this->service->stock($category, $start, $end);
        $categories = $this->service->categories();

        return view(
            'reports.stock',
            compact('products', 'categories')
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

    public function activity(Request $request)
    {
        $activities = $this->service->activity(
            $request->user_id,
            $request->start_date,
            $request->end_date
        );

        $users = \App\Models\User::orderBy('name')->get();

        return view('reports.activity', compact('activities', 'users'));
    }
}
