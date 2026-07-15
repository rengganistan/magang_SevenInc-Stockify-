@extends('layouts.dashboard')

@section('content')

<div class="w-full px-4 py-6 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">

        <div>

            <p class="text-sm text-gray-400 mb-2">
                Dashboard / Pengguna / Edit
            </p>

            <h1 class="text-3xl lg:text-4xl font-bold text-white">
                Edit Pengguna
            </h1>

            <p class="mt-2 text-gray-400">
                Perbarui informasi akun pengguna.
            </p>

        </div>

        <a href="{{ route('users.index') }}"
            class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-gray-700 hover:bg-gray-600 text-white transition">

            ← Kembali

        </a>

    </div>

    {{-- Error Validation --}}
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
    <div class="max-w-5xl rounded-2xl border border-gray-700 bg-gray-800 shadow-xl">

        <form action="{{ route('users.update',$user->id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="p-8">

                {{-- Avatar --}}
                <div class="flex flex-col items-center mb-10">

                    <div
                        class="w-24 h-24 rounded-full bg-blue-600 flex items-center justify-center text-4xl font-bold text-white shadow-lg">

                        {{ strtoupper(substr($user->name,0,1)) }}

                    </div>

                    <h2 class="mt-4 text-2xl font-semibold text-white">

                        {{ $user->name }}

                    </h2>

                    <p class="text-gray-400">

                        {{ $user->email }}

                    </p>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Nama --}}
                    <div>

                        <label class="block mb-2 text-sm font-semibold text-gray-300">

                            Nama Lengkap

                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name',$user->name) }}"

                            class="block w-full rounded-xl border border-gray-600 bg-gray-700 px-4 py-3 text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500">

                    </div>

                    {{-- Email --}}
                    <div>

                        <label class="block mb-2 text-sm font-semibold text-gray-300">

                            Email

                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email',$user->email) }}"

                            class="block w-full rounded-xl border border-gray-600 bg-gray-700 px-4 py-3 text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500">

                    </div>

                    {{-- Password --}}
                    <div>

                        <label class="block mb-2 text-sm font-semibold text-gray-300">

                            Password Baru

                        </label>

                        <input
                            type="password"
                            name="password"

                            placeholder="Kosongkan jika tidak diganti"

                            class="block w-full rounded-xl border border-gray-600 bg-gray-700 px-4 py-3 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">

                        <small class="text-gray-400">

                            Password lama tetap digunakan jika dikosongkan.

                        </small>

                    </div>

                    {{-- Role --}}
                    <div>

                        <label class="block mb-2 text-sm font-semibold text-gray-300">

                            Role

                        </label>

                        <select
                            name="role"

                            class="block w-full rounded-xl border border-gray-600 bg-gray-700 px-4 py-3 text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500">

                            <option value="admin"
                                {{ $user->role=='admin' ? 'selected' : '' }}>
                                Admin
                            </option>

                            <option value="manager"
                                {{ $user->role=='manager' ? 'selected' : '' }}>
                                Manager
                            </option>

                            <option value="staff"
                                {{ $user->role=='staff' ? 'selected' : '' }}>
                                Staff
                            </option>

                        </select>

                    </div>

                </div>

            </div>

            {{-- Footer --}}
            <div class="border-t border-gray-700 p-6">

                <div class="flex flex-col sm:flex-row justify-end gap-3">

                    <a
                        href="{{ route('users.index') }}"
                        class="w-full sm:w-auto text-center px-6 py-3 rounded-xl bg-gray-600 hover:bg-gray-500 text-white transition">

                        Batal

                    </a>

                    <button
                        type="submit"
                        class="w-full sm:w-auto px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white shadow-lg transition">

                        💾 Simpan Perubahan

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection
