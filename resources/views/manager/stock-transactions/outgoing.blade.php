@extends('layouts.dashboard')

@section('content')

<div class="w-full px-4 py-6 sm:px-6 lg:px-8">

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">
        <div>
            <p class="text-sm text-gray-400 mb-2">Dashboard / Barang Keluar</p>
            <h1 class="text-3xl lg:text-4xl font-bold text-white">Barang Keluar</h1>
            <p class="mt-2 text-gray-400">Daftar seluruh transaksi barang keluar.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('manager.dashboard') }}"
                class="px-5 py-3 rounded-xl bg-gray-700 hover:bg-gray-600 text-white">
                ← Dashboard
            </a>
            <a href="{{ route('manager.transactions.create', ['type' => 'Keluar']) }}"
                class="px-5 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white">
                + Catat Barang Keluar
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 rounded-xl border border-green-700 bg-green-900/40 p-4">
        <span class="text-green-300">{{ session('success') }}</span>
    </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-gray-700 bg-gray-800 shadow-xl">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-gray-300">No</th>
                        <th class="px-6 py-4 text-left text-gray-300">Tanggal</th>
                        <th class="px-6 py-4 text-left text-gray-300">Produk</th>
                        <th class="px-6 py-4 text-center text-gray-300">Qty</th>
                        <th class="px-6 py-4 text-center text-gray-300">Status</th>
                        <th class="px-6 py-4 text-left text-gray-300">Petugas</th>
                        <th class="px-6 py-4 text-left text-gray-300">Catatan</th>
                        <th class="px-6 py-4 text-center text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($transactions as $tx)
                    <tr class="hover:bg-gray-700 transition {{ $tx->status === 'Pending' ? 'bg-yellow-900/10' : '' }}">
                        <td class="px-6 py-4 text-white">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-gray-300">
                            {{ \Carbon\Carbon::parse($tx->date)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-white font-semibold">{{ $tx->product->nama ?? '-' }}</td>
                        <td class="px-6 py-4 text-center text-white">{{ $tx->quantity }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($tx->status === 'Pending')
                                <span class="px-3 py-1 rounded-full bg-yellow-500/20 text-yellow-400 border border-yellow-500/40 text-xs font-semibold animate-pulse">
                                    ⏳ Pending
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-red-600/20 text-red-400 text-xs font-semibold">
                                    ✓ {{ $tx->status }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-300">{{ $tx->user->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-400 text-sm">{{ $tx->notes ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                @if($tx->status === 'Pending')
                                <form action="{{ route('manager.transactions.confirm', $tx->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white text-sm font-semibold">
                                        ✓ Konfirmasi
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('manager.transactions.destroy', $tx->id) }}"
                                    method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm">
                                        🗑 Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-16 text-center text-gray-400">
                            Belum ada data barang keluar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
