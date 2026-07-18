<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FinanceReportController extends Controller
{
    public function index(Request $request)
    {
        // Default: bulan ini
        $start = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::now()->startOfMonth();

        $end = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::now()->endOfDay();

        // Ambil semua transaksi dalam periode dengan produk
        $transactions = StockTransaction::with(['product', 'user'])
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->latest('date')
            ->get();

        // ── Hitung nilai keuangan ────────────────────────────────
        // Pemasukan modal = barang masuk × harga_beli
        $transaksiBeli = $transactions->where('type', 'Masuk')
            ->map(function ($tx) {
                $tx->nilai = $tx->quantity * ($tx->product->harga_beli ?? 0);
                return $tx;
            });

        // Nilai penjualan = barang keluar × harga_jual
        $transaksiJual = $transactions->where('type', 'Keluar')
            ->map(function ($tx) {
                $tx->nilai = $tx->quantity * ($tx->product->harga_jual ?? 0);
                return $tx;
            });

        $totalPemasukan  = $transaksiBeli->sum('nilai');   // modal barang masuk
        $totalPengeluaran = $transaksiJual->sum('nilai');  // nilai penjualan keluar
        $selisih          = $totalPengeluaran - $totalPemasukan;

        // Qty total
        $qtyMasuk  = $transaksiBeli->sum('quantity');
        $qtyKeluar = $transaksiJual->sum('quantity');

        // Grafik per bulan (6 bulan terakhir)
        $chartLabels   = [];
        $chartBeli     = [];
        $chartJual     = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $m    = $date->month;
            $y    = $date->year;
            $label = $date->format('M Y');

            $masukBulan = StockTransaction::with('product')
                ->where('type', 'Masuk')
                ->whereMonth('date', $m)
                ->whereYear('date', $y)
                ->get()
                ->sum(fn($tx) => $tx->quantity * ($tx->product->harga_beli ?? 0));

            $keluarBulan = StockTransaction::with('product')
                ->where('type', 'Keluar')
                ->whereMonth('date', $m)
                ->whereYear('date', $y)
                ->get()
                ->sum(fn($tx) => $tx->quantity * ($tx->product->harga_jual ?? 0));

            $chartLabels[] = $label;
            $chartBeli[]   = $masukBulan;
            $chartJual[]   = $keluarBulan;
        }

        // Gabung semua transaksi dengan nilai untuk tabel
        $allTransactions = $transactions->map(function ($tx) {
            if ($tx->type === 'Masuk') {
                $tx->nilai = $tx->quantity * ($tx->product->harga_beli ?? 0);
            } else {
                $tx->nilai = $tx->quantity * ($tx->product->harga_jual ?? 0);
            }
            return $tx;
        });

        return view('reports.finance', compact(
            'allTransactions',
            'totalPemasukan',
            'totalPengeluaran',
            'selisih',
            'qtyMasuk',
            'qtyKeluar',
            'chartLabels',
            'chartBeli',
            'chartJual',
            'start',
            'end'
        ));
    }
}
