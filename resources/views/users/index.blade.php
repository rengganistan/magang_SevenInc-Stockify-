@extends('layouts.dashboard')

@section('content')

<div>

    <div class="flex justify-between items-center mb-8">

        <div>

            <h1 class="text-4xl font-bold text-white">

                User Management

            </h1>

            <p class="mt-2 text-gray-400">

                Kelola seluruh akun pengguna Stockify.

            </p>

        </div>

        <div class="flex gap-3">

            <a
                href="{{ route('admin.dashboard') }}"
                class="px-5 py-3 rounded-lg bg-gray-700 hover:bg-gray-600 text-white transition">

                ← Dashboard

            </a>

            <a
                href="{{ route('users.create') }}"
                class="px-5 py-3 rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition">

                + Add User

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

                    <th class="px-6 py-4 text-gray-200">NO</th>

                    <th class="px-6 py-4 text-gray-200">NAMA</th>

                    <th class="px-6 py-4 text-gray-200">EMAIL</th>

                    <th class="px-6 py-4 text-gray-200">ROLE</th>

                    <th class="px-6 py-4 text-center text-gray-200">ACTION</th>

                </tr>

            </thead>

            <tbody>

                @foreach($users as $user)

                <tr class="border-t border-gray-700 hover:bg-gray-700 transition">

                    <td class="px-6 py-5 text-white">

                        {{ $loop->iteration }}

                    </td>

                    <td class="px-6 py-5 text-white">

                        {{ $user->name }}

                    </td>

                    <td class="px-6 py-5 text-gray-300">

                        {{ $user->email }}

                    </td>

                    <td class="px-6 py-5">

                        @if($user->role=='admin')

                            <span class="px-4 py-1 rounded-full bg-red-600 text-white text-sm">

                                Admin

                            </span>

                        @elseif($user->role=='manager')

                            <span class="px-4 py-1 rounded-full bg-yellow-500 text-white text-sm">

                                Manager

                            </span>

                        @else

                            <span class="px-4 py-1 rounded-full bg-green-600 text-white text-sm">

                                Staff

                            </span>

                        @endif

                    </td>

                    <td class="px-6 py-5">

                        <div class="flex justify-center gap-2">

                            <a
                                href="{{ route('users.edit',$user->id) }}"
                                class="px-4 py-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-white">

                                ✏ Edit

                            </a>

                            <form
                                action="{{ route('users.destroy',$user->id) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus user ini?')">

                                @csrf

                                @method('DELETE')

                                <button
                                    class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white">

                                    🗑 Delete

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection
