@extends('layouts.dashboard')

@section('content')

<div class="w-full px-4 py-6 sm:px-6 lg:px-8">

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">
        <div>
            <p class="text-sm text-gray-400 mb-2">Dashboard / Produk</p>
            <h1 class="text-3xl lg:text-4xl font-bold text-white">Daftar Produk</h1>
            <p class="mt-2 text-gray-400">Lihat informasi dan stok semua produk.</p>
        </div>
        <a href="{{ route('staff.dashboard') }}"
            class="px-5 py-3 rounded-xl bg-gray-700 hover:bg-gray-600 text-white transition">
            ← Dashboard
        </a>
    </div>

    {{-- Statistik --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <div class="bg-gray-800 rounded-xl p-5 border border-gray-700">
            <p class="text-gray-400 text-sm">Total Produk</p>
            <h2 class="text-3xl font-bold text-white mt-2">{{ $totalProducts }}</h2>
        </div>
        <div class="bg-gray-800 rounded-xl p-5 border border-gray-700">
            <p class="text-gray-400 text-sm">Total Kategori</p>
            <h2 class="text-3xl font-bold text-green-400 mt-2">{{ $totalKategori }}</h2>
        </div>
        <a href="{{ route('staff.products.index', ['stok' => 'menipis']) }}"
            class="bg-gray-800 rounded-xl p-5 border {{ ($stokFilter ?? '') === 'menipis' ? 'border-yellow-500' : 'border-gray-700' }} hover:border-yellow-500 transition group block">
            <p class="text-gray-400 text-sm">Stok Menipis</p>
            <h2 class="text-3xl font-bold text-yellow-400 mt-2">{{ $stokMenipis }}</h2>
            <p class="text-xs text-yellow-500 mt-1 group-hover:underline">Lihat Semua →</p>
        </a>
        <a href="{{ route('staff.products.index', ['stok' => 'habis']) }}"
            class="bg-gray-800 rounded-xl p-5 border {{ ($stokFilter ?? '') === 'habis' ? 'border-red-500' : 'border-gray-700' }} hover:border-red-500 transition group block">
            <p class="text-gray-400 text-sm">Stok Habis</p>
            <h2 class="text-3xl font-bold text-red-500 mt-2">{{ $stokHabis }}</h2>
            <p class="text-xs text-red-400 mt-1 group-hover:underline">Lihat Semua →</p>
        </a>
    </div>

    {{-- Search --}}
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-5 mb-6">
        <form action="{{ route('staff.products.index') }}" method="GET" class="flex gap-3">
            <input type="text" name="search" value="{{ $search ?? '' }}"
                placeholder="Cari produk..."
                class="flex-1 rounded-lg bg-gray-700 border border-gray-600 text-white placeholder-gray-400 px-5 py-3">
            <button class="px-5 py-3 rounded-lg bg-blue-600 hover:bg-blue-700 text-white">Cari</button>
            @if($search ?? false)
                <a href="{{ route('staff.products.index') }}" class="px-5 py-3 rounded-lg bg-gray-600 hover:bg-gray-500 text-white">Reset</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl bg-gray-800 border border-gray-700 shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-gray-200">No</th>
                        <th class="px-6 py-4 text-gray-200">Foto</th>
                        <th class="px-6 py-4 text-gray-200">Kode</th>
                        <th class="px-6 py-4 text-gray-200">Nama Produk</th>
                        <th class="px-6 py-4 text-gray-200">Kategori</th>
                        <th class="px-6 py-4 text-gray-200">Supplier</th>
                        <th class="px-6 py-4 text-center text-gray-200">Stok</th>
                        <th class="px-6 py-4 text-center text-gray-200">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr class="border-t border-gray-700 hover:bg-gray-700 transition">
                        <td class="px-6 py-4 text-white">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">
                            @if($product->gambar)
                                <img src="{{ asset('storage/'.$product->gambar) }}"
                                    class="w-12 h-12 rounded-lg object-cover border border-gray-600">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-gray-700 flex items-center justify-center text-gray-400 text-xl">📦</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-blue-400">{{ $product->kode }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-white">{{ $product->nama }}</div>
                            @if($product->deskripsi)
                                <div class="text-xs text-gray-400 mt-0.5">{{ Str::limit($product->deskripsi, 35) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-300">{{ $product->category->nama ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $product->supplier->nama ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($product->stok == 0)
                                <span class="px-3 py-1 rounded-full bg-red-600 text-white text-sm">Habis</span>
                            @elseif($product->stok <= $product->stok_minimum)
                                <span class="px-3 py-1 rounded-full bg-yellow-500 text-white text-sm">{{ $product->stok }}</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-green-600 text-white text-sm">{{ $product->stok }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('staff.products.show', $product->id) }}"
                                class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm transition">
                                🔍 Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-16 text-center text-gray-400">Belum ada produk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>

</div>

@endsection
