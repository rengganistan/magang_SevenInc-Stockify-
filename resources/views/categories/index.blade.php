@extends('layouts.dashboard')

@section('content')

<div>

    <div class="flex justify-between items-center mb-8">

        <div>

            <h1 class="text-4xl font-bold text-white">

                Manajemen Kategori

            </h1>

            <p class="mt-2 text-gray-400">

                Kelola seluruh kategori produk Stockify.

            </p>

        </div>

        <div class="flex gap-3">

            <a
                href="{{ route('admin.dashboard') }}"
                class="px-5 py-3 rounded-lg bg-gray-700 hover:bg-gray-600 text-white transition">

                ← Dashboard

            </a>

            <a
                href="{{ route('categories.create') }}"
                class="px-5 py-3 rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition">

                + Tambah Kategori

            </a>

        </div>

    </div>

    @if(session('success'))

        <div class="mb-6 rounded-lg bg-green-900 border border-green-700 p-4">

            <span class="text-green-300">

                {{ session('success') }}

            </span>

        </div>

    @endif

    <div class="overflow-hidden rounded-xl bg-gray-800 shadow-xl border border-gray-700">

        <table class="w-full text-left">

            <thead class="bg-gray-700">

                <tr>

                    <th class="px-6 py-4 text-gray-200">
                        NO
                    </th>

                    <th class="px-6 py-4 text-gray-200">
                        NAMA KATEGORI
                    </th>

                    <th class="px-6 py-4 text-gray-200">
                        DESKRIPSI
                    </th>

                    <th class="px-6 py-4 text-center text-gray-200">
                        AKSI
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($categories as $category)

                <tr class="border-t border-gray-700 hover:bg-gray-700 transition">

                    <td class="px-6 py-5 text-white">

                        {{ $loop->iteration }}

                    </td>

                    <td class="px-6 py-5 text-white font-medium">

                        {{ $category->nama }}

                    </td>

                    <td class="px-6 py-5 text-gray-300">

                        {{ $category->description }}

                    </td>

                    <td class="px-6 py-5">

                        <div class="flex justify-center gap-2">

                            <a
                                href="{{ route('categories.edit',$category->id) }}"
                                class="px-4 py-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-white">

                                ✏ Edit

                            </a>

                            <form
                                action="{{ route('categories.destroy',$category->id) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">

                                @csrf
                                @method('DELETE')

                                <button
                                    class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white">

                                    🗑 Hapus

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="4"
                        class="text-center py-10 text-gray-400">

                        Belum ada kategori.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
