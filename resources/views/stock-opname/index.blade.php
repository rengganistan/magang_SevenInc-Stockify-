@extends('layouts.dashboard')

@section('content')

<div class="w-full px-4 py-6 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">

        <div>
            <p class="text-sm text-gray-400 mb-2">Dashboard / Stock Opname</p>
            <h1 class="text-3xl lg:text-4xl font-bold text-white">Stock Opname</h1>
            <p class="mt-2 text-gray-400">Riwayat pengecekan dan penyesuaian stok barang.</p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('admin.dashboard') }}"
                class="px-5 py-3 rounded-xl bg-gray-700 hover:bg-gray-600 text-white transition">
                ← Dashboard
            </a>
            <a href="{{ route('stock-opname.create') }}"
                class="px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white transition">
                + Opname Baru
            </a>
        </div>

    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="mb-6 rounded-xl border border-green-700 bg-green-900/40 p-4">
        <span class="text-green-300">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl border border-gray-700 bg-gray-800 shadow-xl">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-gray-300">No</th>
                        <th class="px-6 py-4 text-left text-gray-300">Tanggal</th>
                        <th class="px-6 py-4 text-left text-gray-300">Dilakukan Oleh</th>
                        <th class="px-6 py-4 text-center text-gray-300">Jumlah Item</th>
                        <th class="px-6 py-4 text-center text-gray-300">Status</th>
                        <th class="px-6 py-4 text-left text-gray-300">Catatan</th>
                        <th class="px-6 py-4 text-center text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">

                    @forelse($opnames as $opname)
                    <tr class="hover:bg-gray-700 transition">

                        <td class="px-6 py-4 text-white">{{ $loop->iteration }}</td>

                        <td class="px-6 py-4 text-gray-300">
                            {{ \Carbon\Carbon::parse($opname->tanggal)->format('d M Y') }}
                        </td>

                        <td class="px-6 py-4 text-white font-medium">
                            {{ $opname->user->name }}
                        </td>

                        <td class="px-6 py-4 text-center text-white">
                            {{ $opname->items->count() }} produk
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($opname->status === 'Selesai')
                                <span class="px-3 py-1 rounded-full bg-green-600/30 text-green-400 text-xs font-semibold">
                                    ✅ Selesai
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-yellow-500/20 text-yellow-400 text-xs font-semibold">
                                    📝 Draft
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-gray-400 text-sm">
                            {{ $opname->catatan ? \Illuminate\Support\Str::limit($opname->catatan, 40) : '-' }}
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('stock-opname.show', $opname->id) }}"
                                    class="px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm">
                                    Detail
                                </a>
                                @if($opname->status === 'Draft')
                                <form action="{{ route('stock-opname.destroy', $opname->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Hapus draft opname ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-3 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm">
                                        Hapus
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-16 text-center text-gray-400">
                            Belum ada data stock opname.
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
