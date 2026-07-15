<!DOCTYPE html>
<html lang="en">

<head>

    <title>Edit User</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

</head>

<body class="bg-gray-100">

<div class="max-w-3xl mx-auto p-6">

    <h1 class="text-3xl font-bold mb-6">

        Edit User

    </h1>

    <form action="{{ route('users.update',$user->id) }}" method="POST">

        @csrf
        @method('PUT')

        <div class="mb-4">

            <label class="block mb-2">

                Nama

            </label>

            <input
                type="text"
                name="name"
                value="{{ old('name',$user->name) }}"
                class="w-full border rounded-lg p-2">

        </div>

        <div class="mb-4">

            <label class="block mb-2">

                Email

            </label>

            <input
                type="email"
                name="email"
                value="{{ old('email',$user->email) }}"
                class="w-full border rounded-lg p-2">

        </div>

        <div class="mb-4">

            <label class="block mb-2">

                Password Baru

            </label>

            <input
                type="password"
                name="password"
                class="w-full border rounded-lg p-2">

            <small class="text-gray-500">
                Kosongkan jika tidak ingin mengganti password.
            </small>

        </div>

        <div class="mb-6">

            <label class="block mb-2">

                Role

            </label>

            <select
                name="role"
                class="w-full border rounded-lg p-2">

                <option value="admin"
                    {{ $user->role=='admin'?'selected':'' }}>
                    Admin
                </option>

                <option value="manager"
                    {{ $user->role=='manager'?'selected':'' }}>
                    Manager
                </option>

                <option value="staff"
                    {{ $user->role=='staff'?'selected':'' }}>
                    Staff
                </option>

            </select>

        </div>

        <button
            class="bg-blue-600 text-white px-5 py-2 rounded-lg">

            Update User

        </button>

    </form>

</div>

</body>
</html>
