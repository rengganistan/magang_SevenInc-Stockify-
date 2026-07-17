@extends('layouts.dashboard')

@section('content')

<div class="w-full px-4 py-6 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">

        <div>

            <nav class="flex mb-2 text-sm text-gray-400">

                <span>Dashboard</span>

                <span class="mx-2">/</span>

                <span>Kategori</span>

                <span class="mx-2">/</span>

                <span class="text-white">Edit</span>

            </nav>

            <h1 class="text-3xl lg:text-4xl font-bold text-white">

                Edit Kategori

            </h1>

            <p class="mt-2 text-gray-400">

                Perbarui informasi kategori produk.

            </p>

        </div>

        <a
            href="{{ route('categories.index') }}"
            class="inline-flex items-center justify-center
                   px-5 py-3
                   rounded-lg
                   bg-gray-700
                   hover:bg-gray-600
                   text-white
                   transition">

            ← Kembali

        </a>

    </div>

    {{-- Validation --}}
    @if ($errors->any())

        <div class="mb-6 rounded-lg border border-red-700 bg-red-900/50 p-4">

            <ul class="list-disc list-inside text-red-300">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    {{-- Card --}}
    <div class="max-w-5xl rounded-2xl border border-gray-700 bg-gray-800 shadow-xl">

        <form action="{{ route('categories.update',$category->id) }}" method="POST">

            @csrf

            @method('PUT')

            <div class="p-6 md:p-8">

                <div class="space-y-6">

                    {{-- Nama --}}
                    <div>

                        <label class="block mb-2 text-sm font-semibold text-gray-300">

                            Nama Kategori

                        </label>

                        <input
                            type="text"
                            name="nama"
                            value="{{ old('nama',$category->nama) }}"
                            placeholder="Masukkan nama kategori"

                            class="block
                                   w-full
                                   rounded-xl
                                   border
                                   border-gray-600
                                   bg-gray-700
                                   px-4
                                   py-3
                                   text-white
                                   placeholder-gray-400
                                   focus:border-blue-500
                                   focus:ring-2
                                   focus:ring-blue-500
                                   hover:border-blue-400
                                   transition">

                    </div>

                    {{-- Deskripsi --}}
                    <div>

                        <label class="block mb-2 text-sm font-semibold text-gray-300">

                            Deskripsi

                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            placeholder="Masukkan deskripsi kategori"

                            class="block
                                   w-full
                                   rounded-xl
                                   border
                                   border-gray-600
                                   bg-gray-700
                                   px-4
                                   py-3
                                   text-white
                                   placeholder-gray-400
                                   resize-none
                                   focus:border-blue-500
                                   focus:ring-2
                                   focus:ring-blue-500
                                   hover:border-blue-400
                                   transition">{{ old('description',$category->description) }}</textarea>

                    </div>

                </div>

            </div>

            {{-- Footer --}}
            <div class="border-t border-gray-700 p-6">

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3">

                    <a
                        href="{{ route('categories.index') }}"
                        class="w-full sm:w-auto
                               text-center
                               px-6
                               py-3
                               rounded-xl
                               bg-gray-600
                               hover:bg-gray-500
                               text-white
                               transition">

                        Batal

                    </a>

                    <button
                        type="submit"
                        class="w-full sm:w-auto
                               px-6
                               py-3
                               rounded-xl
                               bg-blue-600
                               hover:bg-blue-700
                               text-white
                               transition
                               shadow-lg">

                        💾 Simpan Perubahan

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection
