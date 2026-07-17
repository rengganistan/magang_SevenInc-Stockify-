@extends('layouts.dashboard')

@section('content')

<div class="w-full px-4 py-6 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">
        <div>
            <p class="text-sm text-gray-400 mb-2">Dashboard / Stock Opname / Baru</p>
            <h1 class="text-3xl lg:text-4xl font-bold text-white">Opname Baru</h1>
            <p class="mt-2 text-gray-400">Isi stok fisik hasil hitung di gudang untuk setiap produk.</p>
        </div>
        <a href="{{ route('stock-opname.index') }}"
            class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-gray-700 hover:bg-gray-600 text-white transition">
            ← Kembali
        </a>
    </div>

    {{-- Error --}}
    @if($errors->any())
    <div class="mb-6 rounded-xl border border-red-700 bg-red-900/40 p-4">
        <ul class="list-disc list-inside text-red-300">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('stock-opname.store') }}" method="POST">
        @csrf

        {{-- Info Opname --}}
        <div class="rounded-2xl border border-gray-700 bg-gray-800 shadow-xl mb-6 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="block mb-2 text-gray-300 font-semibold">Tanggal Opname</label>
                    <input type="date" name="tanggal"
                        value="{{ old('tanggal', date('Y-m-d')) }}"
                        class="w-full rounded-xl border border-gray-600 bg-gray-700 text-white px-4 py-3 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block mb-2 text-gray-300 font-semibold">Catatan <span class="text-gray-500 font-normal">(opsional)</span></label>
                    <input type="text" name="catatan"
                        value="{{ old('catatan') }}"
                        placeholder="Contoh: Opname bulanan Januari"
                        class="w-full rounded-xl border border-gray-600 bg-gray-700 text-white px-4 py-3 focus:ring-2 focus:ring-blue-500">
                </div>

            </div>
        </div>

        {{-- Tabel Produk --}}
        <div class="rounded-2xl border border-gray-700 bg-gray-800 shadow-xl">

            {{-- Search --}}
            <div class="p-4 border-b border-gray-700">
                <input id="searchOpname" type="text" placeholder="Cari produk..."
                    class="w-full md:w-80 rounded-xl border border-gray-600 bg-gray-700 text-white px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full" id="opnameTable">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-gray-300">Produk</th>
                            <th class="px-6 py-4 text-left text-gray-300">Kategori</th>
                            <th class="px-6 py-4 text-center text-gray-300">Stok Sistem</th>
                            <th class="px-6 py-4 text-center text-gray-300">Stok Fisik <span class="text-blue-400">*</span></th>
                            <th class="px-6 py-4 text-center text-gray-300">Selisih</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">

                        @foreach($products as $product)
                        <tr class="hover:bg-gray-700/50 opname-row">

                            <td class="px-6 py-4">
                                <div class="font-semibold text-white product-name">{{ $product->nama }}</div>
                                <div class="text-xs text-gray-400">{{ $product->kode }}</div>
                            </td>

                            <td class="px-6 py-4 text-gray-400 text-sm">
                                {{ $product->category->nama ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="text-white font-semibold stok-sistem" data-stok="{{ $product->stok }}">
                                    {{ $product->stok }}
                                </span>
                                <span class="text-gray-400 text-xs ml-1">{{ $product->satuan }}</span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <input
                                    type="number"
                                    name="stok_fisik[{{ $product->id }}]"
                                    min="0"
                                    value="{{ old('stok_fisik.'.$product->id, $product->stok) }}"
                                    class="stok-fisik w-24 rounded-lg border border-gray-600 bg-gray-900 text-white text-center px-2 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                                    data-sistem="{{ $product->stok }}">
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="selisih-badge px-3 py-1 rounded-full text-sm font-semibold">
                                    0
                                </span>
                            </td>

                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            <div class="border-t border-gray-700 p-6">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <p class="text-sm text-gray-400">
                        💡 Simpan sebagai <strong class="text-white">Draft</strong> dulu. Kamu bisa review sebelum diterapkan ke stok.
                    </p>
                    <div class="flex gap-3">
                        <a href="{{ route('stock-opname.index') }}"
                            class="px-6 py-3 rounded-xl bg-gray-600 hover:bg-gray-500 text-white text-center">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold">
                            💾 Simpan Draft
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </form>

</div>

<script>
    // Hitung selisih real-time
    document.querySelectorAll('.stok-fisik').forEach(function(input) {
        function hitungSelisih() {
            const sistem  = parseInt(input.dataset.sistem) || 0;
            const fisik   = parseInt(input.value) || 0;
            const selisih = fisik - sistem;
            const badge   = input.closest('tr').querySelector('.selisih-badge');

            badge.textContent = (selisih >= 0 ? '+' : '') + selisih;

            if (selisih > 0) {
                badge.className = 'selisih-badge px-3 py-1 rounded-full text-sm font-semibold bg-green-600/20 text-green-400';
            } else if (selisih < 0) {
                badge.className = 'selisih-badge px-3 py-1 rounded-full text-sm font-semibold bg-red-600/20 text-red-400';
            } else {
                badge.className = 'selisih-badge px-3 py-1 rounded-full text-sm font-semibold bg-gray-600/30 text-gray-400';
            }
        }
        input.addEventListener('input', hitungSelisih);
        hitungSelisih(); // init
    });

    // Search
    document.getElementById('searchOpname').addEventListener('keyup', function() {
        const val = this.value.toLowerCase();
        document.querySelectorAll('.opname-row').forEach(function(row) {
            const name = row.querySelector('.product-name').textContent.toLowerCase();
            row.style.display = name.includes(val) ? '' : 'none';
        });
    });
</script>

@endsection
