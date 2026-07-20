<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {

    }

    public function boot(): void
    {
        Paginator::useTailwind();

        // Share recent activity notifications to navbar component
        View::composer('components.navbar-dashboard', function ($view) {

            $navSetting = \Illuminate\Support\Facades\Cache::rememberForever(
                'nav_setting', fn () => Setting::first()
            );

            $lowStockProducts = \Illuminate\Support\Facades\Cache::remember(
                'nav_low_stock', 60, fn () =>
                Product::whereColumn('stok', '<=', 'stok_minimum')
                    ->where('stok', '>', 0)
                    ->orderBy('stok')
                    ->take(5)
                    ->get()
            );

            $pendingCount = \Illuminate\Support\Facades\Cache::remember(
                'nav_pending_count', 30, fn () =>
                \App\Models\StockTransaction::where('status', 'Pending')->count()
            );

            // Untuk admin: activity log terbaru (semua aktivitas)
            $navNotifications = \Illuminate\Support\Facades\Cache::remember(
                'nav_notifications', 30, fn () =>
                ActivityLog::with('user')->latest()->take(7)->get()
            );

            // Untuk manager & staff: transaksi masuk/keluar terbaru
            $navRecentTransactions = \Illuminate\Support\Facades\Cache::remember(
                'nav_recent_transactions', 30, fn () =>
                \App\Models\StockTransaction::with(['product', 'user'])
                    ->latest()
                    ->take(5)
                    ->get()
            );

            $view->with('navNotifications', $navNotifications);
            $view->with('navRecentTransactions', $navRecentTransactions);
            $view->with('navLowStockProducts', $lowStockProducts);
            $view->with('navSetting', $navSetting);
            $view->with('navPendingCount', $pendingCount);
        });
    }
}
