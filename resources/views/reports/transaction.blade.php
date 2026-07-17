@extends('layouts.dashboard')

@section('content')

<div class="w-full px-4 py-6 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">

        <div>

            <p class="text-sm text-gray-400 mb-2">
                Dashboard / Laporan / Transaksi
            </p>

            <h1 class="text-3xl font-bold text-white">
                Laporan Transaksi
            </h1>

            <p class="mt-2 text-gray-400">
                Laporan seluruh transaksi barang masuk dan barang keluar.
            </p>

        </div>

    </div>

    {{-- Filter --}}
    <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow mb-8">

        <form action="{{ route('reports.transaction') }}" method="GET">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">

                <div>

                    <label class="block mb-2 text-gray-300">
                        Jenis
                    </label>

                    <select
                        name="type"
                        class="w-full rounded-xl bg-gray-700 border border-gray-600 text-white px-4 py-3">

                        <option value="">
                            Semua
                        </option>

                        <option
                            value="Masuk"
                            {{ request('type')=='Masuk' ? 'selected' : '' }}>

                            Barang Masuk

                        </option>

                        <option
                            value="Keluar"
                            {{ request('type')=='Keluar' ? 'selected' : '' }}>

                            Barang Keluar

                        </option>

                    </select>

                </div>

                <div>

                    <label class="block mb-2 text-gray-300">

                        Dari Tanggal

                    </label>

                    <input
                        type="date"
                        name="start_date"
                        value="{{ request('start_date') }}"
                        class="w-full rounded-xl bg-gray-700 border border-gray-600 text-white px-4 py-3">

                </div>

                <div>

                    <label class="block mb-2 text-gray-300">

                        Sampai

                    </label>

                    <input
                        type="date"
                        name="end_date"
                        value="{{ request('end_date') }}"
                        class="w-full rounded-xl bg-gray-700 border border-gray-600 text-white px-4 py-3">

                </div>

                <div class="flex items-end">

                    <button
                        class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white">

                        Filter

                    </button>

                </div>

            </div>

        </form>

    </div>

    {{-- Table --}}
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

                        <th class="px-6 py-4 text-left text-gray-300">User</th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-700">

                    @forelse($transactions as $transaction)

                    <tr class="hover:bg-gray-700">

                        <td class="px-6 py-5 text-white">

                            {{ $loop->iteration }}

                        </td>

                        <td class="px-6 py-5 text-gray-300">

                            {{ \Carbon\Carbon::parse($transaction->date)->format('d M Y') }}

                        </td>

                        <td class="px-6 py-5 text-white">

                            {{ $transaction->product->nama }}

                        </td>

                        <td class="px-6 py-5">

                            @if($transaction->type=='Masuk')

                                <span class="px-3 py-1 rounded-full bg-green-600 text-white">

                                    Barang Masuk

                                </span>

                            @else

                                <span class="px-3 py-1 rounded-full bg-red-600 text-white">

                                    Barang Keluar

                                </span>

                            @endif

                        </td>

                        <td class="px-6 py-5 text-center text-white">

                            {{ $transaction->quantity }}

                        </td>

                        <td class="px-6 py-5 text-gray-300">

                            {{ $transaction->status }}

                        </td>

                        <td class="px-6 py-5 text-gray-300">

                            {{ $transaction->user->name }}

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="7"
                            class="py-16 text-center text-gray-400">

                            Tidak ada data transaksi.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
