@extends('layouts.dashboard')

@section('content')

<div class="w-full px-4 py-6 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">

        <div>

            <p class="text-sm text-gray-400 mb-2">
                Dashboard / Kategori / Tambah
            </p>

            <h1 class="text-3xl lg:text-4xl font-bold text-white">
                Tambah Kategori
            </h1>

            <p class="mt-2 text-gray-400">
                Tambahkan kategori produk baru ke dalam sistem.
            </p>

        </div>

        <a href="{{ route('categories.index') }}"
            class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-gray-700 hover:bg-gray-600 text-white transition">

            ← Kembali

        </a>

    </div>

    {{-- Error --}}
    @if ($errors->any())

        <div class="mb-6 rounded-xl border border-red-700 bg-red-900/40 p-4">

            <ul class="list-disc list-inside text-red-300">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    {{-- Card --}}
    <div class="rounded-2xl border border-gray-700 bg-gray-800 shadow-xl">

        <form action="{{ route('categories.store') }}" method="POST">

            @csrf

            <div class="p-8">

                <div class="space-y-6">

                    {{-- Nama --}}
                    <div>

                        <label class="block mb-2 text-gray-300 font-semibold">
                            Nama Kategori
                        </label>

                        <input
                            type="text"
                            name="nama"
                            value="{{ old('nama') }}"
                            placeholder="Masukkan nama kategori"
                            class="w-full rounded-xl border border-gray-600 bg-gray-700 px-4 py-3 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 outline-none transition">

                    </div>

                    {{-- Deskripsi --}}
                    <div>

                        <label class="block mb-2 text-gray-300 font-semibold">
                            Deskripsi
                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            placeholder="Masukkan deskripsi kategori"
                            class="w-full rounded-xl border border-gray-600 bg-gray-700 px-4 py-3 text-white placeholder-gray-400 resize-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500 outline-none transition">{{ old('description') }}</textarea>

                    </div>

                </div>

            </div>

            {{-- Footer --}}
            <div class="border-t border-gray-700 p-6">

                <div class="flex flex-col sm:flex-row justify-end gap-3">

                    <a href="{{ route('categories.index') }}"
                        class="px-6 py-3 rounded-xl bg-gray-600 hover:bg-gray-500 text-white text-center transition">

                        Batal

                    </a>

                    <button
                        type="submit"
                        class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white transition">

                        💾 Simpan Kategori

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection
