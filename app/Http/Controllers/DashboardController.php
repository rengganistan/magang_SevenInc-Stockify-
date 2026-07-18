<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function admin()
    {
        // Statistik utama
        $totalProducts      = Product::count();
        $totalUsers         = User::count();

        // Transaksi bulan ini
        $thisMonth          = Carbon::now()->month;
        $thisYear           = Carbon::now()->year;

        $incomingThisMonth  = StockTransaction::where('type', 'Masuk')
            ->whereMonth('date', $thisMonth)
            ->whereYear('date', $thisYear)
            ->count();

        $outgoingThisMonth  = StockTransaction::where('type', 'Keluar')
            ->whereMonth('date', $thisMonth)
            ->whereYear('date', $thisYear)
            ->count();

        // Stok menipis (stok <= stok_minimum)
        $lowStock           = Product::whereColumn('stok', '<=', 'stok_minimum')
            ->where('stok', '>', 0)
            ->count();

        // Aktivitas terbaru (10 terakhir) dari activity_logs
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Data grafik: transaksi masuk & keluar per bulan (12 bulan terakhir)
        $chartLabels  = [];
        $chartIncoming = [];
        $chartOutgoing = [];

        for ($i = 11; $i >= 0; $i--) {
            $date   = Carbon::now()->subMonths($i);
            $label  = $date->format('M Y');
            $m      = $date->month;
            $y      = $date->year;

            $chartLabels[]   = $label;
            $chartIncoming[] = StockTransaction::where('type', 'Masuk')
                ->whereMonth('date', $m)
                ->whereYear('date', $y)
                ->count();
            $chartOutgoing[] = StockTransaction::where('type', 'Keluar')
                ->whereMonth('date', $m)
                ->whereYear('date', $y)
                ->count();
        }

        // Top 5 produk stok terbanyak untuk grafik stok
        $topProducts = Product::orderBy('stok', 'desc')
            ->take(8)
            ->get();

        return view('dashboards.admin', compact(
            'totalProducts',
            'totalUsers',
            'incomingThisMonth',
            'outgoingThisMonth',
            'lowStock',
            'recentActivities',
            'chartLabels',
            'chartIncoming',
            'chartOutgoing',
            'topProducts'
        ));
    }

    public function manager()
    {
        return view('dashboards.manager');
    }

    public function staff()
    {
        return view('dashboards.staff');
    }
}
