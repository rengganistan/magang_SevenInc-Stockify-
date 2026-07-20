@extends('layouts.dashboard')

@section('content')

<div class="w-full px-4 py-6 sm:px-6 lg:px-8 space-y-6 bg-gray-900 min-h-screen">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <p class="text-sm text-gray-400 mb-1">Dashboard / Produk</p>
            <h1 class="text-3xl font-bold text-white">Manajemen Produk</h1>
            <p class="text-gray-400 text-sm mt-1">Kelola seluruh produk dalam sistem inventory.</p>
        </div>
        <div class="flex flex-wrap gap-3 items-center">
            <a href="{{ route('admin.dashboard') }}"
                class="px-4 py-2.5 rounded-lg bg-gray-700 hover:bg-gray-600 text-white text-sm">
                ← Dashboard
            </a>
            <a href="{{ route('products.export') }}"
                class="px-4 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white text-sm">
                📥 Export Excel
            </a>
            <form action="{{ route('products.import') }}" method="POST"
                enctype="multipart/form-data" class="flex gap-2 items-center">
                @csrf
                <input type="file" name="file" required accept=".xlsx,.xls,.csv"
                    class="bg-gray-700 text-white rounded-lg px-3 py-2 border border-gray-600 text-sm">
                <button class="px-4 py-2.5 rounded-lg bg-yellow-500 hover:bg-yellow-600 text-white text-sm">
                    📤 Import
                </button>
            </form>
            <a href="{{ route('products.create') }}"
                class="px-4 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold">
                + Tambah Produk
            </a>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('error'))
    <div class="rounded-lg bg-red-900/40 border border-red-700 p-4 text-red-300 text-sm">
        {{ session('error') }}
    </div>
    @endif

    @if(session('import_errors'))
    <div class="rounded-lg bg-red-900/40 border border-red-700 p-4">
        <h3 class="font-bold text-white mb-2 text-sm">Import gagal pada beberapa data:</h3>
        <ul class="list-disc ml-5 text-red-300 text-sm space-y-1">
            @foreach(session('import_errors') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

        <div class="bg-gray-800 rounded-xl p-5 border border-gray-700">
            <p class="text-gray-400 text-xs mb-1">Total Produk</p>
            <p class="text-3xl font-bold text-white">{{ $totalProducts }}</p>
            <p class="text-xs text-gray-500 mt-1">Semua produk terdaftar</p>
        </div>

        <div class="bg-gray-800 rounded-xl p-5 border border-gray-700">
            <p class="text-gray-400 text-xs mb-1">Total Kategori</p>
            <p class="text-3xl font-bold text-blue-400">{{ $totalKategori }}</p>
            <p class="text-xs text-gray-500 mt-1">Kategori aktif</p>
        </div>

        <a href="{{ route('products.index', ['stok' => 'menipis']) }}"
            class="bg-gray-800 rounded-xl p-5 border {{ $stokFilter === 'menipis' ? 'border-yellow-500' : 'border-gray-700' }} text-left hover:border-yellow-500 transition group block">
            <p class="text-gray-400 text-xs mb-1">Stok Menipis</p>
            <p class="text-3xl font-bold text-yellow-400">{{ $stokMenipis }}</p>
            <p class="text-xs text-yellow-500 mt-1 group-hover:underline">Lihat Semua →</p>
        </a>

        <a href="{{ route('products.index', ['stok' => 'habis']) }}"
            class="bg-gray-800 rounded-xl p-5 border {{ $stokFilter === 'habis' ? 'border-red-500' : 'border-gray-700' }} text-left hover:border-red-500 transition group block">
            <p class="text-gray-400 text-xs mb-1">Stok Habis</p>
            <p class="text-3xl font-bold text-red-500">{{ $stokHabis }}</p>
            <p class="text-xs text-red-400 mt-1 group-hover:underline">Lihat Semua →</p>
        </a>

    </div>

    {{-- Search & Filter --}}
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-4">
        <div class="flex flex-col md:flex-row gap-3 items-center">
            <form method="GET" action="{{ route('products.index') }}" class="flex-1 flex gap-3">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari kode atau nama produk..."
                    class="flex-1 rounded-lg bg-gray-700 border border-gray-600 text-white placeholder-gray-400 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                <button type="submit"
                    class="px-4 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm">
                    Cari
                </button>
                @if($search)
                <a href="{{ route('products.index') }}"
                    class="px-4 py-2.5 rounded-lg bg-gray-600 hover:bg-gray-500 text-white text-sm">
                    Reset
                </a>
                @endif
            </form>
            @if($stokFilter)
            <div class="flex items-center gap-2 shrink-0">
                @if($stokFilter === 'menipis')
                    <span class="px-3 py-1.5 rounded-full text-xs font-semibold bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">
                        🟡 Filter: Stok Menipis
                    </span>
                @else
                    <span class="px-3 py-1.5 rounded-full text-xs font-semibold bg-red-500/20 text-red-400 border border-red-500/30">
                        🔴 Filter: Stok Habis
                    </span>
                @endif
                <a href="{{ route('products.index') }}" class="text-gray-400 hover:text-white text-xs whitespace-nowrap">✕ Reset filter</a>
            </div>
            @endif
        </div>
    </div>

    {{-- Table --}}
    <div class="rounded-xl bg-gray-800 border border-gray-700 shadow-xl overflow-hidden">

        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-700">
            <p class="text-sm text-gray-400">
                Menampilkan <span class="text-white font-medium">{{ $products->firstItem() }}–{{ $products->lastItem() }}</span>
                dari <span class="text-white font-medium">{{ $products->total() }}</span> produk
            </p>
            <span id="activeFilterInfo" class="text-xs text-gray-500"></span>
        </div>

        <div class="overflow-x-auto">
            <table id="productTable" class="w-full text-left text-sm">
                <thead class="bg-gray-700/60">
                    <tr>
                        <th class="px-5 py-3 text-gray-300 font-medium w-10">No</th>
                        <th class="px-5 py-3 text-gray-300 font-medium w-16">Foto</th>
                        <th class="px-5 py-3 text-gray-300 font-medium">Kode</th>
                        <th class="px-5 py-3 text-gray-300 font-medium">Nama Produk</th>
                        <th class="px-5 py-3 text-gray-300 font-medium">Kategori</th>
                        <th class="px-5 py-3 text-gray-300 font-medium">Harga</th>
                        <th class="px-5 py-3 text-gray-300 font-medium">Stok</th>
                        <th class="px-5 py-3 text-center text-gray-300 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody id="productBody">
                    @forelse($products as $product)
                    <tr class="product-row border-t border-gray-700/60 hover:bg-gray-700/40 transition"
                        data-stok="{{ $product->stok }}"
                        data-stok-minimum="{{ $product->stok_minimum }}">

                        <td class="px-5 py-4 text-gray-400">
                            {{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}
                        </td>

                        <td class="px-5 py-4">
                            @if($product->gambar)
                                <img src="{{ asset('storage/'.$product->gambar) }}"
                                    class="w-12 h-12 rounded-lg object-cover border border-gray-600">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-gray-700 flex items-center justify-center text-xl">
                                    📦
                                </div>
                            @endif
                        </td>

                        <td class="px-5 py-4">
                            <span class="font-mono text-blue-400 text-xs font-semibold">{{ $product->kode }}</span>
                        </td>

                        <td class="px-5 py-4">
                            <div class="font-semibold text-white">{{ $product->nama }}</div>
                            @if($product->deskripsi)
                                <div class="text-xs text-gray-400 mt-0.5">{{ Str::limit($product->deskripsi, 45) }}</div>
                            @endif
                        </td>

                        <td class="px-5 py-4 text-gray-300 text-sm">
                            {{ $product->category->nama ?? '-' }}
                        </td>

                        <td class="px-5 py-4">
                            <div class="text-green-400 font-semibold">Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">Modal: Rp {{ number_format($product->harga_beli, 0, ',', '.') }}</div>
                        </td>

                        <td class="px-5 py-4">
                            @if($product->stok == 0)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-red-600/20 text-red-400 border border-red-500/30 text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-400 inline-block"></span> Habis
                                </span>
                            @elseif($product->stok <= $product->stok_minimum)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-400 inline-block"></span> {{ $product->stok }} pcs
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-green-600/20 text-green-400 border border-green-500/30 text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-400 inline-block"></span> {{ $product->stok }} pcs
                                </span>
                            @endif
                            <div class="text-xs text-gray-600 mt-0.5">min: {{ $product->stok_minimum }}</div>
                        </td>

                        <td class="px-5 py-4">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('products.edit', $product->id) }}"
                                    class="px-3 py-1.5 rounded-lg bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold transition">
                                    ✏ Edit
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-3 py-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-semibold transition">
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
                                <div class="text-5xl mb-3">📦</div>
                                <h2 class="text-xl font-bold text-white">Belum Ada Produk</h2>
                                <p class="text-gray-400 mt-2 text-sm">Tambahkan produk pertama untuk memulai inventory.</p>
                                <a href="{{ route('products.create') }}"
                                    class="mt-5 px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm">
                                    + Tambah Produk
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-5 py-4 border-t border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-gray-500">
                Halaman {{ $products->currentPage() }} dari {{ $products->lastPage() }}
            </p>
            {{ $products->withQueryString()->links() }}
        </div>

    </div>

</div>

@endsection
