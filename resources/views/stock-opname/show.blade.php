@extends('layouts.dashboard')

@section('content')

<div class="w-full px-4 py-6 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">
        <div>
            <p class="text-sm text-gray-400 mb-2">Dashboard / Stock Opname / Detail</p>
            <h1 class="text-3xl lg:text-4xl font-bold text-white">Detail Opname #{{ $opname->id }}</h1>
            <p class="mt-2 text-gray-400">
                {{ \Carbon\Carbon::parse($opname->tanggal)->format('d F Y') }}
                · oleh <span class="text-white">{{ $opname->user->name }}</span>
            </p>
        </div>
        <a href="{{ route('stock-opname.index') }}"
            class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-gray-700 hover:bg-gray-600 text-white transition">
            ← Kembali
        </a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="mb-6 rounded-xl border border-green-700 bg-green-900/40 p-4">
        <span class="text-green-300">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Info Card --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

        <div class="rounded-xl border border-gray-700 bg-gray-800 p-4">
            <p class="text-xs text-gray-400 mb-1">Status</p>
            @if($opname->status === 'Selesai')
                <span class="px-3 py-1 rounded-full bg-green-600/30 text-green-400 text-sm font-semibold">✅ Selesai</span>
            @else
                <span class="px-3 py-1 rounded-full bg-yellow-500/20 text-yellow-400 text-sm font-semibold">📝 Draft</span>
            @endif
        </div>

        <div class="rounded-xl border border-gray-700 bg-gray-800 p-4">
            <p class="text-xs text-gray-400 mb-1">Jumlah Item</p>
            <p class="text-2xl font-bold text-white">{{ $opname->items->count() }} produk</p>
        </div>

        <div class="rounded-xl border border-gray-700 bg-gray-800 p-4">
            <p class="text-xs text-gray-400 mb-1">Total Selisih</p>
            @php $totalSelisih = $opname->items->sum('selisih'); @endphp
            <p class="text-2xl font-bold {{ $totalSelisih > 0 ? 'text-green-400' : ($totalSelisih < 0 ? 'text-red-400' : 'text-white') }}">
                {{ $totalSelisih >= 0 ? '+' : '' }}{{ $totalSelisih }}
            </p>
        </div>

    </div>

    @if($opname->catatan)
    <div class="mb-6 rounded-xl border border-gray-600 bg-gray-800/50 p-4">
        <p class="text-xs text-gray-400 mb-1">Catatan</p>
        <p class="text-gray-300">{{ $opname->catatan }}</p>
    </div>
    @endif

    {{-- Tabel Item --}}
    <div class="rounded-2xl border border-gray-700 bg-gray-800 shadow-xl mb-6">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-gray-300">No</th>
                        <th class="px-6 py-4 text-left text-gray-300">Produk</th>
                        <th class="px-6 py-4 text-center text-gray-300">Stok Sistem</th>
                        <th class="px-6 py-4 text-center text-gray-300">Stok Fisik</th>
                        <th class="px-6 py-4 text-center text-gray-300">Selisih</th>
                        <th class="px-6 py-4 text-center text-gray-300">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @foreach($opname->items as $item)
                    <tr class="hover:bg-gray-700/50">
                        <td class="px-6 py-4 text-white">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-white">{{ $item->product->nama }}</div>
                            <div class="text-xs text-gray-400">{{ $item->product->kode }}</div>
                        </td>
                        <td class="px-6 py-4 text-center text-white">{{ $item->stok_sistem }}</td>
                        <td class="px-6 py-4 text-center text-white font-semibold">{{ $item->stok_fisik }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($item->selisih > 0)
                                <span class="px-3 py-1 rounded-full bg-green-600/20 text-green-400 text-sm font-semibold">
                                    +{{ $item->selisih }}
                                </span>
                            @elseif($item->selisih < 0)
                                <span class="px-3 py-1 rounded-full bg-red-600/20 text-red-400 text-sm font-semibold">
                                    {{ $item->selisih }}
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-gray-600/30 text-gray-400 text-sm">
                                    0
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center text-sm">
                            @if($item->selisih > 0)
                                <span class="text-green-400">Lebih</span>
                            @elseif($item->selisih < 0)
                                <span class="text-red-400">Kurang</span>
                            @else
                                <span class="text-gray-400">Sesuai</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Action --}}
    @if($opname->status === 'Draft')
    <div class="rounded-2xl border border-yellow-600/40 bg-yellow-900/10 p-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-white font-semibold mb-1">Terapkan Penyesuaian Stok</h3>
                <p class="text-sm text-gray-400">
                    Setelah diselesaikan, stok produk akan diperbarui sesuai hasil opname
                    dan transaksi penyesuaian akan dicatat otomatis. <strong class="text-yellow-400">Aksi ini tidak bisa dibatalkan.</strong>
                </p>
            </div>
            <form action="{{ route('stock-opname.selesaikan', $opname->id) }}"
                method="POST"
                onsubmit="return confirm('Yakin ingin menerapkan penyesuaian stok? Aksi ini tidak bisa dibatalkan.')">
                @csrf
                <button type="submit"
                    class="whitespace-nowrap px-6 py-3 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold">
                    ✅ Selesaikan & Terapkan Stok
                </button>
            </form>
        </div>
    </div>
    @else
    <div class="rounded-2xl border border-green-700/40 bg-green-900/10 p-5">
        <p class="text-green-400 text-sm font-medium">
            ✅ Opname ini sudah diselesaikan. Stok produk telah diperbarui dan transaksi penyesuaian telah dicatat.
        </p>
    </div>
    @endif

</div>

@endsection
