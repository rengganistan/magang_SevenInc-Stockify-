<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        // Satu query, group by bulan & type
        $start = Carbon::now()->subMonths(11)->startOfMonth();

        $chartRaw = StockTransaction::selectRaw(
                "type, DATE_FORMAT(date, '%Y-%m') as ym, COUNT(*) as total"
            )
            ->where('date', '>=', $start)
            ->groupBy('type', 'ym')
            ->get()
            ->groupBy('type');

        $chartLabels   = [];
        $chartIncoming = [];
        $chartOutgoing = [];

        for ($i = 11; $i >= 0; $i--) {
            $date  = Carbon::now()->subMonths($i);
            $ym    = $date->format('Y-m');

            $chartLabels[]   = $date->format('M Y');
            $chartIncoming[] = (int) optional(
                $chartRaw->get('Masuk', collect())->firstWhere('ym', $ym)
            )->total ?? 0;
            $chartOutgoing[] = (int) optional(
                $chartRaw->get('Keluar', collect())->firstWhere('ym', $ym)
            )->total ?? 0;
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
        $today     = Carbon::today();
        $thisMonth = Carbon::now()->month;
        $thisYear  = Carbon::now()->year;

        // Stat cards
        $totalProducts = Product::count();

        $lowStock = Product::whereColumn('stok', '<=', 'stok_minimum')
            ->where('stok', '>', 0)
            ->count();

        $incomingToday = StockTransaction::where('type', 'Masuk')
            ->whereDate('date', $today)
            ->count();

        $outgoingToday = StockTransaction::where('type', 'Keluar')
            ->whereDate('date', $today)
            ->count();

        $incomingThisMonth = StockTransaction::where('type', 'Masuk')
            ->whereMonth('date', $thisMonth)
            ->whereYear('date', $thisYear)
            ->count();

        $outgoingThisMonth = StockTransaction::where('type', 'Keluar')
            ->whereMonth('date', $thisMonth)
            ->whereYear('date', $thisYear)
            ->count();

        // Grafik 6 bulan terakhir — satu query, group by bulan & type
        $chartStart = Carbon::now()->subMonths(5)->startOfMonth();

        $chartRaw = StockTransaction::selectRaw(
                "type, DATE_FORMAT(date, '%Y-%m') as ym, COUNT(*) as total"
            )
            ->where('date', '>=', $chartStart)
            ->groupBy('type', 'ym')
            ->get()
            ->groupBy('type');

        $chartLabels   = [];
        $chartIncoming = [];
        $chartOutgoing = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $ym   = $date->format('Y-m');

            $chartLabels[]   = $date->format('M Y');
            $chartIncoming[] = (int) optional(
                $chartRaw->get('Masuk', collect())->firstWhere('ym', $ym)
            )->total ?? 0;
            $chartOutgoing[] = (int) optional(
                $chartRaw->get('Keluar', collect())->firstWhere('ym', $ym)
            )->total ?? 0;
        }

        // Produk stok menipis (list)
        $lowStockProducts = Product::with('category')
            ->whereColumn('stok', '<=', 'stok_minimum')
            ->orderBy('stok')
            ->take(8)
            ->get();

        // Transaksi terbaru
        $recentTransactions = StockTransaction::with(['product', 'user'])
            ->latest()
            ->take(8)
            ->get();

        return view('dashboards.manager', compact(
            'totalProducts',
            'lowStock',
            'incomingToday',
            'outgoingToday',
            'incomingThisMonth',
            'outgoingThisMonth',
            'chartLabels',
            'chartIncoming',
            'chartOutgoing',
            'lowStockProducts',
            'recentTransactions'
        ));
    }

    public function staff()
    {
        return view('dashboards.staff');
    }
}
