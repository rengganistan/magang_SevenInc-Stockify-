@extends('layouts.dashboard')

@section('content')

<div class="w-full px-4 py-6 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">

        <div>

            <p class="text-sm text-gray-400 mb-2">
                Dashboard / Transaksi
            </p>

            <h1 class="text-3xl lg:text-4xl font-bold text-white">
                Manajemen Transaksi
            </h1>

            <p class="mt-2 text-gray-400">
                Kelola transaksi barang masuk dan barang keluar.
            </p>

        </div>

        <div class="flex flex-wrap gap-3">

            <a href="{{ route('admin.dashboard') }}"
                class="px-5 py-3 rounded-xl bg-gray-700 hover:bg-gray-600 text-white transition">

                ← Dashboard

            </a>

            <a href="{{ route('stock-transactions.create') }}"
                class="px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white transition">

                + Tambah Transaksi

            </a>

        </div>

    </div>

    {{-- Success --}}
    @if(session('success'))

    <div class="mb-6 rounded-xl border border-green-700 bg-green-900/40 p-4">

        <span class="text-green-300">

            {{ session('success') }}

        </span>

    </div>

    @endif

    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl border border-gray-700 bg-gray-800 shadow-xl">

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-gray-700">

                    <tr>

                        <th class="px-6 py-4 text-left text-gray-300">
                            No
                        </th>

                        <th class="px-6 py-4 text-left text-gray-300">
                            Tanggal
                        </th>

                        <th class="px-6 py-4 text-left text-gray-300">
                            Produk
                        </th>

                        <th class="px-6 py-4 text-left text-gray-300">
                            Jenis
                        </th>

                        <th class="px-6 py-4 text-center text-gray-300">
                            Qty
                        </th>

                        <th class="px-6 py-4 text-center text-gray-300">
                            Status
                        </th>

                        <th class="px-6 py-4 text-left text-gray-300">
                            User
                        </th>

                        <th class="px-6 py-4 text-center text-gray-300">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-700">

                    @forelse($transactions as $transaction)

                    <tr class="hover:bg-gray-700 transition">

                        <td class="px-6 py-5 text-white">

                            {{ $loop->iteration }}

                        </td>

                        <td class="px-6 py-5 text-gray-300">

                            {{ \Carbon\Carbon::parse($transaction->date)->format('d M Y') }}

                        </td>

                        <td class="px-6 py-5 text-white font-semibold">

                            {{ $transaction->product->nama }}

                        </td>

                        <td class="px-6 py-5">

                            @if($transaction->type == 'Masuk')

                                <span class="px-3 py-1 rounded-full bg-green-600 text-white text-sm">

                                    Barang Masuk

                                </span>

                            @else

                                <span class="px-3 py-1 rounded-full bg-red-600 text-white text-sm">

                                    Barang Keluar

                                </span>

                            @endif

                        </td>

                        <td class="px-6 py-5 text-center text-white font-semibold">

                            {{ $transaction->quantity }}

                        </td>

                        <td class="px-6 py-5 text-center">

                            @switch($transaction->status)

                                @case('Pending')

                                <span class="px-3 py-1 rounded-full bg-yellow-500 text-white">

                                    Pending

                                </span>

                                @break

                                @case('Diterima')

                                <span class="px-3 py-1 rounded-full bg-green-600 text-white">

                                    Diterima

                                </span>

                                @break

                                @case('Ditolak')

                                <span class="px-3 py-1 rounded-full bg-red-600 text-white">

                                    Ditolak

                                </span>

                                @break

                                @default

                                <span class="px-3 py-1 rounded-full bg-blue-600 text-white">

                                    Dikeluarkan

                                </span>

                            @endswitch

                        </td>

                        <td class="px-6 py-5 text-gray-300">

                            {{ $transaction->user->name }}

                        </td>

                        <td class="px-6 py-5">

                            <div class="flex justify-center gap-2">

                                <a href="{{ route('stock-transactions.edit',$transaction->id) }}"
                                    class="px-4 py-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-white">

                                    Edit

                                </a>

                                <form action="{{ route('stock-transactions.destroy',$transaction->id) }}"
                                    method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        onclick="return confirm('Yakin ingin menghapus transaksi?')"
                                        class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white">

                                        Hapus

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="8"
                            class="py-20 text-center text-gray-400">

                            Belum ada transaksi.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
