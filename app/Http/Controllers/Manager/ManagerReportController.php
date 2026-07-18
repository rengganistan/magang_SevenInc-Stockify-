<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ReportService;

class ManagerReportController extends Controller
{
    protected ReportService $service;

    public function __construct(ReportService $service)
    {
        $this->service = $service;
    }

    public function stock(Request $request)
    {
        $category   = $request->category;
        $start      = $request->start_date;
        $end        = $request->end_date;
        $products   = $this->service->stock($category, $start, $end);
        $categories = $this->service->categories();

        return view('manager.reports.stock', compact('products', 'categories'));
    }

    public function transaction(Request $request)
    {
        $transactions = $this->service->transaction(
            $request->type,
            $request->start_date,
            $request->end_date
        );

        return view('manager.reports.transaction', compact('transactions'));
    }
}
