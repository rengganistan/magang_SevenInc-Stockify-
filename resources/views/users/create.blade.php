@extends('layouts.dashboard')

@section('content')

<div class="w-full px-4 py-6 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">

        <div>

            <p class="text-sm text-gray-400 mb-2">
                Dashboard / Pengguna / Tambah
            </p>

            <h1 class="text-3xl lg:text-4xl font-bold text-white">
                Tambah Pengguna
            </h1>

            <p class="mt-2 text-gray-400">
                Tambahkan akun pengguna baru ke dalam sistem.
            </p>

        </div>

        <a
            href="{{ route('users.index') }}"
            class="inline-flex items-center justify-center
                   px-5 py-3
                   rounded-xl
                   bg-gray-700
                   hover:bg-gray-600
                   text-white
                   transition">

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

    <div class="max-w-5xl rounded-2xl border border-gray-700 bg-gray-800 shadow-xl">

        <form action="{{ route('users.store') }}" method="POST">

            @csrf

            <div class="p-6 md:p-8">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Nama --}}
                    <div>

                        <label class="block mb-2 text-sm font-semibold text-gray-300">

                            Nama Lengkap

                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Masukkan nama lengkap"

                            class="block w-full rounded-xl border border-gray-600 bg-gray-700 px-4 py-3 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">

                    </div>

                    {{-- Email --}}
                    <div>

                        <label class="block mb-2 text-sm font-semibold text-gray-300">

                            Email

                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="contoh@email.com"

                            class="block w-full rounded-xl border border-gray-600 bg-gray-700 px-4 py-3 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">

                    </div>

                    {{-- Password --}}
                    <div>

                        <label class="block mb-2 text-sm font-semibold text-gray-300">

                            Password

                        </label>

                        <input
                            type="password"
                            name="password"
                            placeholder="Masukkan password"

                            class="block w-full rounded-xl border border-gray-600 bg-gray-700 px-4 py-3 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">

                    </div>

                    {{-- Role --}}
                    <div>

                        <label class="block mb-2 text-sm font-semibold text-gray-300">

                            Role

                        </label>

                        <select
                            name="role"

                            class="block w-full rounded-xl border border-gray-600 bg-gray-700 px-4 py-3 text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500">

                            <option value="admin">
                                Admin
                            </option>

                            <option value="manager">
                                Manajer Gudang
                            </option>

                            <option value="staff">
                                Staff Gudang
                            </option>

                        </select>

                    </div>

                </div>

            </div>

            <div class="border-t border-gray-700 p-6">

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3">

                    <a
                        href="{{ route('users.index') }}"
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
                               shadow-lg
                               transition">

                        💾 Simpan Pengguna

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection
