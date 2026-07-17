@extends('layouts.dashboard')

@section('content')

<div class="w-full px-4 py-6 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">

        <div>

            <p class="text-sm text-gray-400 mb-2">
                Dashboard / Pengaturan
            </p>

            <h1 class="text-4xl font-bold text-white">
                Pengaturan Aplikasi
            </h1>

            <p class="mt-2 text-gray-400">
                Kelola informasi umum aplikasi Stockify.
            </p>

        </div>

        <a href="{{ route('admin.dashboard') }}"
            class="px-5 py-3 rounded-xl bg-gray-700 hover:bg-gray-600 text-white transition">

            ← Dashboard

        </a>

    </div>

    {{-- Alert --}}
    @if(session('success'))

        <div class="mb-6 rounded-xl border border-green-700 bg-green-900/40 p-4">

            <span class="text-green-300">

                {{ session('success') }}

            </span>

        </div>

    @endif

    {{-- Card --}}
    <div class="rounded-2xl border border-gray-700 bg-gray-800 shadow-xl">

        <form
            action="{{ route('settings.update',$setting->id) }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="p-8">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                    {{-- Nama Aplikasi --}}
                    <div>

                        <label class="block mb-2 text-gray-300 font-semibold">
                            Nama Aplikasi
                        </label>

                        <input
                            type="text"
                            name="app_name"
                            value="{{ old('app_name',$setting->app_name) }}"
                            class="w-full rounded-xl border border-gray-600 bg-gray-700 text-white px-4 py-3">

                    </div>

                    {{-- Email --}}
                    <div>

                        <label class="block mb-2 text-gray-300 font-semibold">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email',$setting->email) }}"
                            class="w-full rounded-xl border border-gray-600 bg-gray-700 text-white px-4 py-3">

                    </div>

                    {{-- Phone --}}
                    <div>

                        <label class="block mb-2 text-gray-300 font-semibold">
                            Nomor Telepon
                        </label>

                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone',$setting->phone) }}"
                            class="w-full rounded-xl border border-gray-600 bg-gray-700 text-white px-4 py-3">

                    </div>

                    {{-- Logo --}}
                    <div>

                        <label class="block mb-2 text-gray-300 font-semibold">
                            Logo
                        </label>

                        <input
                            type="file"
                            name="logo"
                            class="w-full rounded-xl border border-gray-600 bg-gray-700 text-white px-4 py-3">

                    </div>

                </div>

                {{-- Preview Logo --}}
                @if($setting->logo)

                <div class="mt-8">

                    <label class="block mb-3 text-gray-300 font-semibold">
                        Logo Saat Ini
                    </label>

                    <img
                        src="{{ asset('storage/'.$setting->logo) }}"
                        class="h-28 rounded-xl border border-gray-600">

                </div>

                @endif

                {{-- Address --}}
                <div class="mt-8">

                    <label class="block mb-2 text-gray-300 font-semibold">

                        Alamat

                    </label>

                    <textarea
                        name="address"
                        rows="5"
                        class="w-full rounded-xl border border-gray-600 bg-gray-700 text-white px-4 py-3">{{ old('address',$setting->address) }}</textarea>

                </div>

            </div>

            <div class="border-t border-gray-700 p-6">

                <div class="flex justify-end">

                    <button
                        class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white">

                        💾 Simpan Pengaturan

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection
