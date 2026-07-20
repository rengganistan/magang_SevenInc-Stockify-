@extends('layouts.dashboard')

@section('content')

<div class="p-4 bg-gray-900 min-h-screen">

    <div class="flex flex-wrap gap-3 mb-6">

        <a href="{{ route('manager.dashboard') }}"
            class="px-5 py-3 rounded-lg bg-gray-700 hover:bg-gray-600 text-white">
            ← Dashboard
        </a>

        <a href="{{ route('manager.products.create') }}"
            class="px-5 py-3 rounded-lg bg-blue-600 hover:bg-blue-700 text-white">
            + Tambah Produk
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

        <a href="{{ route('manager.products.index', ['stok' => 'menipis']) }}"
            class="bg-gray-800 rounded-xl p-5 border {{ ($stokFilter ?? '') === 'menipis' ? 'border-yellow-500' : 'border-gray-700' }} hover:border-yellow-500 transition group block">
            <p class="text-gray-400 text-sm">Stok Menipis</p>
            <h2 class="text-3xl font-bold text-yellow-400 mt-2">{{ $stokMenipis }}</h2>
            <p class="text-xs text-yellow-500 mt-1 group-hover:underline">Lihat Semua →</p>
        </a>

        <a href="{{ route('manager.products.index', ['stok' => 'habis']) }}"
            class="bg-gray-800 rounded-xl p-5 border {{ ($stokFilter ?? '') === 'habis' ? 'border-red-500' : 'border-gray-700' }} hover:border-red-500 transition group block">
            <p class="text-gray-400 text-sm">Stok Habis</p>
            <h2 class="text-3xl font-bold text-red-500 mt-2">{{ $stokHabis }}</h2>
            <p class="text-xs text-red-400 mt-1 group-hover:underline">Lihat Semua →</p>
        </a>

    </div>

    {{-- Search --}}
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-5 mb-6">
        <form action="{{ route('manager.products.index') }}" method="GET" class="flex gap-3">
            <input type="text" name="search" value="{{ $search ?? '' }}"
                placeholder="Cari produk..."
                class="flex-1 rounded-lg bg-gray-700 border border-gray-600 text-white placeholder-gray-400 px-5 py-3">
            <button class="px-5 py-3 rounded-lg bg-blue-600 hover:bg-blue-700 text-white">Cari</button>
            @if($search ?? false)
                <a href="{{ route('manager.products.index') }}" class="px-5 py-3 rounded-lg bg-gray-600 hover:bg-gray-500 text-white">Reset</a>
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
                        <th class="px-6 py-4 text-gray-200">Harga</th>
                        <th class="px-6 py-4 text-gray-200">Stok</th>
                        <th class="px-6 py-4 text-center text-gray-200">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr class="border-t border-gray-700 hover:bg-gray-700 transition duration-200">
                        <td class="px-6 py-5 text-white">{{ $loop->iteration }}</td>
                        <td class="px-6 py-5">
                            @if($product->gambar)
                                <img src="{{ asset('storage/'.$product->gambar) }}"
                                    class="w-14 h-14 rounded-lg object-cover border border-gray-600">
                            @else
                                <div class="w-14 h-14 rounded-lg bg-gray-700 flex items-center justify-center text-gray-400">📦</div>
                            @endif
                        </td>
                        <td class="px-6 py-5">
                            <span class="font-semibold text-blue-400">{{ $product->kode }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="font-semibold text-white">{{ $product->nama }}</div>
                            @if($product->deskripsi)
                                <div class="text-sm text-gray-400 mt-1">{{ Str::limit($product->deskripsi, 40) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-gray-300">{{ $product->category->nama ?? '-' }}</td>
                        <td class="px-6 py-5">
                            <div class="text-green-400 font-semibold">Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</div>
                            <div class="text-xs text-gray-400">Modal: Rp {{ number_format($product->harga_beli, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-5">
                            @if($product->stok == 0)
                                <span class="px-3 py-1 rounded-full bg-red-600 text-white text-sm">Habis</span>
                            @elseif($product->stok <= $product->stok_minimum)
                                <span class="px-3 py-1 rounded-full bg-yellow-500 text-white text-sm">{{ $product->stok }} pcs</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-green-600 text-white text-sm">{{ $product->stok }} pcs</span>
                            @endif
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('manager.products.show', $product->id) }}"
                                    class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition">
                                    🔍 Detail
                                </a>
                                <a href="{{ route('manager.products.edit', $product->id) }}"
                                    class="px-4 py-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-white transition">
                                    ✏ Edit
                                </a>
                                <form action="{{ route('manager.products.destroy', $product->id) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white transition">
                                        🗑 Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="text-6xl mb-3">📦</div>
                                <h2 class="text-2xl font-bold text-white">Belum Ada Produk</h2>
                                <a href="{{ route('manager.products.create') }}"
                                    class="mt-6 px-6 py-3 rounded-lg bg-blue-600 hover:bg-blue-700 text-white">
                                    + Tambah Produk
                                </a>
                            </div>
                        </td>
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
