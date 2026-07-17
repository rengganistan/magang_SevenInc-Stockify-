@extends('layouts.dashboard')

@section('content')

<div class="p-6">

    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 rounded-xl shadow">

        <div class="p-6 border-b dark:border-gray-700">

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">

                Add Category

            </h2>

        </div>

        <form action="{{ route('categories.store') }}" method="POST">

            @csrf

            <div class="p-6">

                <div class="mb-5">

                    <label class="block mb-2">

                        Category Nama

                    </label>

                    <input
                        type="text"
                        name="nama"
                        value="{{ old('nama') }}"
                        class="w-full rounded-lg border p-3">

                    @error('nama')

                    <p class="text-red-500 mt-1">

                        {{ $message }}

                    </p>

                    @enderror

                </div>

                <div class="mb-5">

                    <label class="block mb-2">

                        Description

                    </label>

                    <textarea
                        name="description"
                        rows="4"
                        class="w-full rounded-lg border p-3">{{ old('description') }}</textarea>

                </div>

            </div>

            <div class="flex justify-end gap-3 p-6 border-t dark:border-gray-700">

                <a href="{{ route('categories.index') }}"
                    class="px-5 py-2 rounded-lg bg-gray-500 text-white">

                    Back

                </a>

                <button
                    class="px-5 py-2 rounded-lg bg-blue-600 text-white">

                    Save

                </button>

            </div>

        </form>

    </div>

</div>

@endsection
