@extends('layouts.dashboard')

@section('content')

<div class="w-full px-4 py-6 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-6 mb-8">

        <div>

            <p class="text-sm text-gray-400 mb-2">
                Dashboard / Supplier / Edit
            </p>

            <h1 class="text-3xl font-bold text-white">
                Edit Supplier
            </h1>

            <p class="mt-2 text-gray-400">
                Perbarui informasi supplier.
            </p>

        </div>

        <a href="{{ route('suppliers.index') }}"
           class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-gray-700 hover:bg-gray-600 text-white transition">

            ← Kembali

        </a>

    </div>

    {{-- Validation Error --}}
    @if ($errors->any())

        <div class="mb-6 rounded-xl border border-red-700 bg-red-900/40 p-4">

            <ul class="list-disc list-inside text-red-300">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    {{-- Card --}}
    <div class="rounded-2xl border border-gray-700 bg-gray-800 shadow-xl">

        <form
            action="{{ route('suppliers.update',$supplier->id) }}"
            method="POST">

            @csrf
            @method('PUT')

            <div class="p-8">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Nama --}}
                    <div>

                        <label class="block mb-2 text-gray-300 font-semibold">

                            Nama Supplier

                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name',$supplier->name) }}"
                            class="w-full rounded-xl border border-gray-600 bg-gray-700 px-4 py-3 text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 outline-none">

                    </div>

                    {{-- Email --}}
                    <div>

                        <label class="block mb-2 text-gray-300 font-semibold">

                            Email

                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email',$supplier->email) }}"
                            class="w-full rounded-xl border border-gray-600 bg-gray-700 px-4 py-3 text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 outline-none">

                    </div>

                    {{-- Telepon --}}
                    <div>

                        <label class="block mb-2 text-gray-300 font-semibold">

                            Nomor Telepon

                        </label>

                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone',$supplier->phone) }}"
                            class="w-full rounded-xl border border-gray-600 bg-gray-700 px-4 py-3 text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 outline-none">

                    </div>

                </div>

                {{-- Alamat --}}
                <div class="mt-6">

                    <label class="block mb-2 text-gray-300 font-semibold">

                        Alamat Supplier

                    </label>

                    <textarea
                        name="address"
                        rows="5"
                        class="w-full rounded-xl border border-gray-600 bg-gray-700 px-4 py-3 text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 outline-none resize-none">{{ old('address',$supplier->address) }}</textarea>

                </div>

            </div>

            {{-- Footer --}}
            <div class="border-t border-gray-700 p-6">

                <div class="flex flex-col sm:flex-row justify-end gap-3">

                    <a
                        href="{{ route('suppliers.index') }}"
                        class="px-6 py-3 rounded-xl bg-gray-600 hover:bg-gray-500 text-white text-center transition">

                        Batal

                    </a>

                    <button
                        type="submit"
                        class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white transition">

                        💾 Simpan Perubahan

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection
