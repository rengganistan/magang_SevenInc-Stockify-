@extends('layouts.dashboard')

@section('content')

<div class="w-full px-4 py-6 sm:px-6 lg:px-8">

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">
        <div>
            <p class="text-sm text-gray-400 mb-2">Dashboard / Laporan / Transaksi</p>
            <h1 class="text-3xl lg:text-4xl font-bold text-white">Laporan Barang Masuk & Keluar</h1>
            <p class="mt-2 text-gray-400">Laporan seluruh transaksi barang masuk dan keluar.</p>
        </div>
        <a href="{{ route('manager.dashboard') }}"
            class="px-5 py-3 rounded-xl bg-gray-700 hover:bg-gray-600 text-white transition">
            ← Dashboard
        </a>
    </div>

    {{-- Filter --}}
    <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 mb-6">
        <form action="{{ route('manager.reports.transaction') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                <div>
                    <label class="block mb-2 text-gray-300 text-sm font-medium">Jenis Transaksi</label>
                    <select name="type"
                        class="w-full rounded-xl bg-gray-700 border border-gray-600 text-white px-4 py-3">
                        <option value="">Semua</option>
                        <option value="Masuk" {{ request('type') == 'Masuk' ? 'selected' : '' }}>Barang Masuk</option>
                        <option value="Keluar" {{ request('type') == 'Keluar' ? 'selected' : '' }}>Barang Keluar</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-2 text-gray-300 text-sm font-medium">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="w-full rounded-xl bg-gray-700 border border-gray-600 text-white px-4 py-3">
                </div>
                <div>
                    <label class="block mb-2 text-gray-300 text-sm font-medium">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="w-full rounded-xl bg-gray-700 border border-gray-600 text-white px-4 py-3">
                </div>
                <div class="flex items-end gap-2">
                    <button class="flex-1 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium">
                        🔍 Filter
                    </button>
                    @if(request('type') || request('start_date') || request('end_date'))
                        <a href="{{ route('manager.reports.transaction') }}"
                            class="px-4 py-3 bg-gray-600 hover:bg-gray-500 rounded-xl text-white">✕</a>
                    @endif
                </div>
            </div>

            {{-- Active filter badges --}}
            @if(request('type') || request('start_date') || request('end_date'))
            <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-700">
                <span class="text-xs text-gray-400">Filter aktif:</span>
                @if(request('type'))
                    <span class="px-2 py-1 rounded-full bg-blue-600/20 text-blue-400 text-xs">{{ request('type') === 'Masuk' ? 'Barang Masuk' : 'Barang Keluar' }}</span>
                @endif
                @if(request('start_date'))
                    <span class="px-2 py-1 rounded-full bg-blue-600/20 text-blue-400 text-xs">
                        Dari: {{ \Carbon\Carbon::parse(request('start_date'))->format('d M Y') }}
                    </span>
                @endif
                @if(request('end_date'))
                    <span class="px-2 py-1 rounded-full bg-blue-600/20 text-blue-400 text-xs">
                        Sampai: {{ \Carbon\Carbon::parse(request('end_date'))->format('d M Y') }}
                    </span>
                @endif
            </div>
            @endif
        </form>
    </div>

    {{-- Summary Cards --}}
    @php
        $totalMasuk  = $transactions->where('type', 'Masuk')->count();
        $totalKeluar = $transactions->where('type', 'Keluar')->count();
        $qtyMasuk    = $transactions->where('type', 'Masuk')->sum('quantity');
        $qtyKeluar   = $transactions->where('type', 'Keluar')->sum('quantity');
    @endphp
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <div class="rounded-xl border border-gray-700 bg-gray-800 p-4 text-center">
            <p class="text-xs text-gray-400 mb-1">Total Transaksi Masuk</p>
            <p class="text-2xl font-bold text-green-400">{{ $totalMasuk }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $qtyMasuk }} unit</p>
        </div>
        <div class="rounded-xl border border-gray-700 bg-gray-800 p-4 text-center">
            <p class="text-xs text-gray-400 mb-1">Total Transaksi Keluar</p>
            <p class="text-2xl font-bold text-red-400">{{ $totalKeluar }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $qtyKeluar }} unit</p>
        </div>
        <div class="rounded-xl border border-gray-700 bg-gray-800 p-4 text-center">
            <p class="text-xs text-gray-400 mb-1">Total Semua Transaksi</p>
            <p class="text-2xl font-bold text-white">{{ $transactions->count() }}</p>
        </div>
        <div class="rounded-xl border border-gray-700 bg-gray-800 p-4 text-center">
            <p class="text-xs text-gray-400 mb-1">Net Unit (Masuk - Keluar)</p>
            @php $selisih = $qtyMasuk - $qtyKeluar; @endphp
            <p class="text-2xl font-bold {{ $selisih >= 0 ? 'text-green-400' : 'text-red-400' }}">
                {{ $selisih >= 0 ? '+' : '' }}{{ $selisih }}
            </p>
            <p class="text-xs text-gray-500 mt-1">
                {{ $selisih >= 0 ? 'Stok bertambah' : 'Stok berkurang' }} dalam periode ini
            </p>
        </div>
    </div>

    {{-- Table --}}
    @php
        $showAll   = request()->boolean('show_all');
        $displayed = $showAll ? $transactions : $transactions->take(25);
        $remaining = $transactions->count() - 25;
    @endphp

    <div class="overflow-hidden rounded-2xl border border-gray-700 bg-gray-800 shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-gray-300">No</th>
                        <th class="px-6 py-4 text-left text-gray-300">Tanggal</th>
                        <th class="px-6 py-4 text-left text-gray-300">Produk</th>
                        <th class="px-6 py-4 text-left text-gray-300">Jenis</th>
                        <th class="px-6 py-4 text-center text-gray-300">Qty</th>
                        <th class="px-6 py-4 text-left text-gray-300">Status</th>
                        <th class="px-6 py-4 text-left text-gray-300">Petugas</th>
                        <th class="px-6 py-4 text-left text-gray-300">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($displayed as $transaction)
                    <tr class="hover:bg-gray-700 transition">
                        <td class="px-6 py-4 text-white">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-gray-300 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($transaction->date)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-white font-medium">{{ $transaction->product->nama ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($transaction->type == 'Masuk')
                                <span class="px-3 py-1 rounded-full bg-green-600/20 text-green-400 text-xs font-semibold">↑ Masuk</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-red-600/20 text-red-400 text-xs font-semibold">↓ Keluar</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-white">{{ $transaction->quantity }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-lg bg-gray-700 text-gray-300 text-xs">{{ $transaction->status }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-300">{{ $transaction->user->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-400 text-sm max-w-xs truncate">{{ $transaction->notes ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-16 text-center text-gray-400">
                            Tidak ada data transaksi untuk filter yang dipilih.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer lihat semua --}}
        @if($transactions->count() > 25)
        <div class="border-t border-gray-700 px-6 py-4 flex items-center justify-between">
            <p class="text-sm text-gray-400">
                Menampilkan <span class="text-white font-semibold">{{ $displayed->count() }}</span>
                dari <span class="text-white font-semibold">{{ $transactions->count() }}</span> transaksi
            </p>
            @if(!$showAll)
                <a href="{{ request()->fullUrlWithQuery(['show_all' => '1']) }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition">
                    Lihat Semua (+{{ $remaining }} lainnya)
                </a>
            @else
                <a href="{{ request()->fullUrlWithQuery(['show_all' => null]) }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gray-600 hover:bg-gray-500 text-white text-sm font-semibold transition">
                    Sembunyikan
                </a>
            @endif
        </div>
        @endif
    </div>

</div>

@endsection
