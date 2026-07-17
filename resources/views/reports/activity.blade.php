@extends('layouts.dashboard')

@section('content')

<div class="w-full px-4 py-6 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">

        <div>

            <p class="text-sm text-gray-400 mb-2">
                Dashboard / Laporan / Aktivitas User
            </p>

            <h1 class="text-3xl font-bold text-white">
                Laporan Aktivitas Pengguna
            </h1>

            <p class="mt-2 text-gray-400">
                Menampilkan aktivitas transaksi yang dilakukan pengguna.
            </p>

        </div>

    </div>

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
                            User
                        </th>

                        <th class="px-6 py-4 text-left text-gray-300">
                            Aktivitas
                        </th>

                        <th class="px-6 py-4 text-left text-gray-300">
                            Produk
                        </th>

                        <th class="px-6 py-4 text-center text-gray-300">
                            Qty
                        </th>

                        <th class="px-6 py-4 text-left text-gray-300">
                            Tanggal
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-700">

                    @forelse($activities as $activity)

                    <tr class="hover:bg-gray-700 transition">

                        <td class="px-6 py-5 text-white">

                            {{ $loop->iteration }}

                        </td>

                        <td class="px-6 py-5 text-white font-semibold">

                            {{ $activity->user->name }}

                        </td>

                        <td class="px-6 py-5">

                            @if($activity->type == 'Masuk')

                                <span class="px-3 py-1 rounded-full bg-green-600 text-white">

                                    Menambahkan Barang

                                </span>

                            @else

                                <span class="px-3 py-1 rounded-full bg-red-600 text-white">

                                    Mengeluarkan Barang

                                </span>

                            @endif

                        </td>

                        <td class="px-6 py-5 text-gray-300">

                            {{ $activity->product->nama }}

                        </td>

                        <td class="px-6 py-5 text-center text-white">

                            {{ $activity->quantity }}

                        </td>

                        <td class="px-6 py-5 text-gray-300">

                            {{ \Carbon\Carbon::parse($activity->date)->format('d M Y') }}

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="6"
                            class="py-16 text-center text-gray-400">

                            Belum ada aktivitas pengguna.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
