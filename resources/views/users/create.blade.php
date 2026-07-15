
<!DOCTYPE html>
<html lang="en">

<head>


@vite(['resources/css/app.css','resources/js/app.js'])

</head>

<body>
<div class="p-6">

    <h1 class="text-3xl font-bold mb-6">
        Tambah User
    </h1>

    <form action="{{ route('users.store') }}" method="POST">

        @csrf

        <div class="mb-4">
            <label class="block mb-2">Nama</label>

            <input
                type="text"
                name="name"
                class="w-full border rounded-lg p-2">
        </div>

        <div class="mb-4">
            <label class="block mb-2">Email</label>

            <input
                type="email"
                name="email"
                class="w-full border rounded-lg p-2">
        </div>

        <div class="mb-4">
            <label class="block mb-2">Password</label>

            <input
                type="password"
                name="password"
                class="w-full border rounded-lg p-2">
        </div>

        <div class="mb-6">

            <label class="block mb-2">
                Role
            </label>

            <select
                name="role"
                class="w-full border rounded-lg p-2">

                <option value="admin">
                    Admin
                </option>

                <option value="manager">
                    Manager
                </option>

                <option value="staff" selected>
                    Staff
                </option>

            </select>

        </div>

        <button
            class="bg-blue-600 text-white px-5 py-2 rounded-lg">

            Simpan

        </button>

    </form>

</div>
</body>

</html>
