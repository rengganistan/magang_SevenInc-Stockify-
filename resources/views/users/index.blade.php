@extends('layouts.dashboard')

@section('content')

<div class="w-full px-4 py-6 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">

        <div>

            <p class="text-sm text-gray-400 mb-2">

                Dashboard / Pengguna

            </p>

            <h1 class="text-3xl lg:text-4xl font-bold text-white">

                Manajemen Pengguna

            </h1>

            <p class="mt-2 text-gray-400">

                Kelola seluruh akun pengguna Stockify.

            </p>

        </div>

        <div class="flex flex-col sm:flex-row gap-3">

            <a
                href="{{ route('admin.dashboard') }}"
                class="inline-flex justify-center items-center
                       px-5 py-3
                       rounded-xl
                       bg-gray-700
                       hover:bg-gray-600
                       text-white
                       transition">

                ← Dashboard

            </a>

            <a
                href="{{ route('users.create') }}"
                class="inline-flex justify-center items-center
                       px-5 py-3
                       rounded-xl
                       bg-blue-600
                       hover:bg-blue-700
                       text-white
                       transition">

                + Tambah User

            </a>

        </div>

    </div>

    {{-- Alert --}}
    @if(session('success'))

        <div class="mb-6 rounded-xl border border-green-700 bg-green-900/40 p-4">

            <div class="text-green-300 font-medium">

                {{ session('success') }}

            </div>

        </div>

    @endif

    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl border border-gray-700 bg-gray-800 shadow-xl">

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-gray-700">

                    <tr>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-300">

                            No

                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-300">

                            Nama

                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-300">

                            Email

                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-300">

                            Role

                        </th>

                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-300">

                            Aksi

                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-700">

                    @forelse($users as $user)

                    <tr class="hover:bg-gray-700 transition">

                        <td class="px-6 py-5 text-white">

                            {{ $loop->iteration }}

                        </td>

                        <td class="px-6 py-5">

                            <div class="font-semibold text-white">

                                {{ $user->name }}

                            </div>

                        </td>

                        <td class="px-6 py-5 text-gray-300">

                            {{ $user->email }}

                        </td>

                        <td class="px-6 py-5">

                            @switch($user->role)

                                @case('admin')

                                    <span class="inline-flex rounded-full bg-red-600 px-3 py-1 text-sm font-medium text-white">

                                        Admin

                                    </span>

                                    @break

                                @case('manager')

                                    <span class="inline-flex rounded-full bg-yellow-500 px-3 py-1 text-sm font-medium text-white">

                                        Manager Gudang

                                    </span>

                                    @break

                                @default

                                    <span class="inline-flex rounded-full bg-green-600 px-3 py-1 text-sm font-medium text-white">

                                        Staff Gudang

                                    </span>

                            @endswitch

                        </td>

                        <td class="px-6 py-5">

                            <div class="flex justify-center gap-2">

                                <a
                                    href="{{ route('users.edit',$user->id) }}"
                                    class="rounded-lg bg-amber-500 px-4 py-2 text-white hover:bg-amber-600 transition">

                                    ✏ Edit

                                </a>

                                <form
                                    action="{{ route('users.destroy',$user->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus user ini?')">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="rounded-lg bg-red-600 px-4 py-2 text-white hover:bg-red-700 transition">

                                        🗑 Hapus

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="5" class="py-16 text-center text-gray-400">

                            Belum ada data pengguna.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
