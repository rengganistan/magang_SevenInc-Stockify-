@extends('layouts.dashboard')

@section('content')

<div class="w-full px-4 py-6 sm:px-6 lg:px-8 space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-white">Dashboard</h1>
        <p class="text-sm text-gray-400 mt-1">
            Selamat datang, <span class="text-white font-medium">{{ auth()->user()->name }}</span>
            — {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
        </p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

        <div class="rounded-xl border border-gray-700 bg-gray-800 p-4">
            <p class="text-xs text-gray-400 mb-1">Total Produk</p>
            <p class="text-3xl font-bold text-white">{{ $totalProducts }}</p>
            <a href="{{ route('products.index') }}" class="text-xs text-blue-400 hover:underline mt-1 block">Lihat produk →</a>
        </div>

        <div class="rounded-xl border border-gray-700 bg-gray-800 p-4">
            <p class="text-xs text-gray-400 mb-1">Masuk Bulan Ini</p>
            <p class="text-3xl font-bold text-green-400">{{ $incomingThisMonth }}</p>
            <a href="{{ route('transactions.incoming') }}" class="text-xs text-green-400 hover:underline mt-1 block">Lihat transaksi →</a>
        </div>

        <div class="rounded-xl border border-gray-700 bg-gray-800 p-4">
            <p class="text-xs text-gray-400 mb-1">Keluar Bulan Ini</p>
            <p class="text-3xl font-bold text-red-400">{{ $outgoingThisMonth }}</p>
            <a href="{{ route('transactions.outgoing') }}" class="text-xs text-red-400 hover:underline mt-1 block">Lihat transaksi →</a>
        </div>

        <div class="rounded-xl border border-gray-700 bg-gray-800 p-4">
            <p class="text-xs text-gray-400 mb-1">Stok Menipis</p>
            <p class="text-3xl font-bold text-yellow-400">{{ $lowStock }}</p>
            <a href="{{ route('products.index') }}" class="text-xs text-yellow-400 hover:underline mt-1 block">Periksa stok →</a>
        </div>

    </div>

    {{-- Grafik + Aktivitas --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">

        {{-- Grafik Transaksi (2/3 lebar) --}}
        <div class="xl:col-span-2 rounded-xl border border-gray-700 bg-gray-800 p-5">

            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-white">Transaksi 6 Bulan Terakhir</h3>
                <div class="flex gap-3 text-xs text-gray-400">
                    <span class="flex items-center gap-1"><span class="inline-block w-2 h-2 rounded-full bg-green-400"></span>Masuk</span>
                    <span class="flex items-center gap-1"><span class="inline-block w-2 h-2 rounded-full bg-red-400"></span>Keluar</span>
                </div>
            </div>

            <canvas id="transactionChart" height="80"></canvas>

        </div>

        {{-- Stok Bar (1/3 lebar) --}}
        <div class="rounded-xl border border-gray-700 bg-gray-800 p-5">

            <h3 class="text-sm font-semibold text-white mb-4">Stok Tertinggi</h3>

            <canvas id="stockChart" height="180"></canvas>

        </div>

    </div>

    {{-- Aktivitas Terbaru --}}
    <div class="rounded-xl border border-gray-700 bg-gray-800">

        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-700">
            <h3 class="text-sm font-semibold text-white">Aktivitas Terbaru</h3>
            <a href="{{ route('reports.activity') }}" class="text-xs text-blue-400 hover:underline">Lihat semua →</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-700/50">
                        <th class="px-5 py-3 text-left text-gray-400 font-medium">Pengguna</th>
                        <th class="px-5 py-3 text-left text-gray-400 font-medium">Aktivitas</th>
                        <th class="px-5 py-3 text-left text-gray-400 font-medium">Produk</th>
                        <th class="px-5 py-3 text-center text-gray-400 font-medium">Qty</th>
                        <th class="px-5 py-3 text-left text-gray-400 font-medium">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($recentActivities as $activity)
                    <tr class="hover:bg-gray-700/40 transition">
                        <td class="px-5 py-3 text-white font-medium">{{ $activity->user->name }}</td>
                        <td class="px-5 py-3">
                            @if($activity->type === 'Masuk')
                                <span class="px-2 py-0.5 rounded-full bg-green-600/20 text-green-400 text-xs">Masuk</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full bg-red-600/20 text-red-400 text-xs">Keluar</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-gray-300">{{ $activity->product->nama ?? '-' }}</td>
                        <td class="px-5 py-3 text-center text-white">{{ $activity->quantity }}</td>
                        <td class="px-5 py-3 text-gray-400 text-xs">{{ \Carbon\Carbon::parse($activity->date)->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-10 text-center text-gray-500 text-sm">Belum ada aktivitas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Ambil 6 bulan terakhir dari data 12 bulan
    const allLabels   = @json($chartLabels);
    const allIncoming = @json($chartIncoming);
    const allOutgoing = @json($chartOutgoing);

    const labels   = allLabels.slice(-6);
    const incoming = allIncoming.slice(-6);
    const outgoing = allOutgoing.slice(-6);

    const productNames  = @json($topProducts->pluck('nama'));
    const productStocks = @json($topProducts->pluck('stok'));

    // Line chart transaksi
    new Chart(document.getElementById('transactionChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Masuk',
                    data: incoming,
                    borderColor: '#4ade80',
                    backgroundColor: 'rgba(74,222,128,0.08)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3,
                    pointBackgroundColor: '#4ade80',
                    borderWidth: 2,
                },
                {
                    label: 'Keluar',
                    data: outgoing,
                    borderColor: '#f87171',
                    backgroundColor: 'rgba(248,113,113,0.08)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3,
                    pointBackgroundColor: '#f87171',
                    borderWidth: 2,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { color: '#6b7280', font: { size: 11 } }, grid: { color: '#1f2937' } },
                y: { ticks: { color: '#6b7280', font: { size: 11 }, stepSize: 1 }, grid: { color: '#374151' }, beginAtZero: true }
            }
        }
    });

    // Bar chart stok
    new Chart(document.getElementById('stockChart'), {
        type: 'bar',
        data: {
            labels: productNames,
            datasets: [{
                data: productStocks,
                backgroundColor: 'rgba(99,102,241,0.7)',
                borderRadius: 4,
                borderSkipped: false,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { color: '#6b7280', font: { size: 10 } }, grid: { color: '#374151' }, beginAtZero: true },
                y: { ticks: { color: '#9ca3af', font: { size: 10 } }, grid: { display: false } }
            }
        }
    });
</script>

@endsection
