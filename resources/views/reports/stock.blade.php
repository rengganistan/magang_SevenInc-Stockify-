@extends('layouts.dashboard')

@section('content')

<div class="w-full px-4 py-6 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">

        <div>

            <p class="text-sm text-gray-400 mb-2">
                Dashboard / Laporan / Laporan Stok
            </p>

            <h1 class="text-3xl lg:text-4xl font-bold text-white">
                Laporan Stok Barang
            </h1>

            <p class="mt-2 text-gray-400">
                Menampilkan seluruh stok barang berdasarkan kategori.
            </p>

        </div>

    </div>

    {{-- Filter --}}
    <div class="bg-gray-800 rounded-2xl border border-gray-700 shadow-lg p-6 mb-8">

        <form method="GET"
            action="{{ route('reports.stock') }}">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                <div>

                    <label class="block mb-2 text-gray-300">
                        Kategori
                    </label>

                    <select
                        name="category"
                        class="w-full rounded-xl bg-gray-700 border border-gray-600 text-white px-4 py-3">

                        <option value="">

                            Semua Kategori

                        </option>

                        @foreach($categories as $category)

                        <option
                            value="{{ $category->id }}"
                            {{ request('category')==$category->id ? 'selected' : '' }}>

                            {{ $category->nama }}

                        </option>

                        @endforeach

                    </select>

                </div>

                <div class="flex items-end">

                    <button
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded-xl text-white">

                        Cari

                    </button>

                </div>

            </div>

        </form>

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
                            Produk
                        </th>

                        <th class="px-6 py-4 text-left text-gray-300">
                            Kategori
                        </th>

                        <th class="px-6 py-4 text-left text-gray-300">
                            Supplier
                        </th>

                        <th class="px-6 py-4 text-center text-gray-300">
                            Stok
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-700">

                    @forelse($products as $product)

                    <tr class="hover:bg-gray-700 transition">

                        <td class="px-6 py-5 text-white">

                            {{ $loop->iteration }}

                        </td>

                        <td class="px-6 py-5 text-white font-semibold">

                            {{ $product->nama }}

                        </td>

                        <td class="px-6 py-5 text-gray-300">

                            {{ $product->category->nama ?? '-' }}

                        </td>

                        <td class="px-6 py-5 text-gray-300">

                            {{ $product->supplier->nama ?? '-' }}

                        </td>

                        <td class="px-6 py-5 text-center">

                            @if($product->stok > 10)

                                <span class="px-4 py-2 rounded-full bg-green-600 text-white">

                                    {{ $product->stok }}

                                </span>

                            @elseif($product->stok > 0)

                                <span class="px-4 py-2 rounded-full bg-yellow-500 text-white">

                                    {{ $product->stok }}

                                </span>

                            @else

                                <span class="px-4 py-2 rounded-full bg-red-600 text-white">

                                    Habis

                                </span>

                            @endif

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="5"
                            class="text-center py-16 text-gray-400">

                            Tidak ada data.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
