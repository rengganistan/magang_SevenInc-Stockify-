<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockTransaction;
use Carbon\Carbon;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $today     = Carbon::today();
        $thisMonth = Carbon::now()->month;
        $thisYear  = Carbon::now()->year;

        // Transaksi pending yang perlu dikonfirmasi (semua, bukan hanya milik sendiri)
        $pendingIncoming = StockTransaction::with(['product', 'user'])
            ->where('type', 'Masuk')
            ->where('status', 'Pending')
            ->latest()
            ->get();

        $pendingOutgoing = StockTransaction::with(['product', 'user'])
            ->where('type', 'Keluar')
            ->where('status', 'Pending')
            ->latest()
            ->get();

        // Stat hari ini (yang sudah dikonfirmasi)
        $incomingToday = StockTransaction::where('type', 'Masuk')
            ->where('status', '!=', 'Pending')
            ->whereDate('date', $today)->count();

        $outgoingToday = StockTransaction::where('type', 'Keluar')
            ->where('status', '!=', 'Pending')
            ->whereDate('date', $today)->count();

        // Stok menipis
        $lowStock = Product::whereColumn('stok', '<=', 'stok_minimum')
            ->where('stok', '>', 0)->count();

        $lowStockProducts = Product::with('category')
            ->whereColumn('stok', '<=', 'stok_minimum')
            ->orderBy('stok')->take(5)->get();

        return view('dashboards.staff', compact(
            'pendingIncoming',
            'pendingOutgoing',
            'incomingToday',
            'outgoingToday',
            'lowStock',
            'lowStockProducts'
        ));
    }
}
