
<!DOCTYPE html>
<html lang="en">

<head>

<title>admin</title>

@vite(['resources/css/app.css','resources/js/app.js'])

</head>

<body>
<div class="p-6">

    <div class="flex justify-between items-center mb-6">

        <h1 class="text-3xl font-bold">
            User Management
        </h1>
          <a
        href="{{ route('users.create') }}"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg">

        + Add User

    </a>

    </div>

    <div class="overflow-x-auto">

        <table class="min-w-full bg-white border">

            <thead class="bg-gray-200">

                <tr>

                    <th class="px-4 py-2">No</th>

                    <th class="px-4 py-2">Nama</th>

                    <th class="px-4 py-2">Email</th>

                    <th class="px-4 py-2">Role</th>

                </tr>

            </thead>

            <tbody>

                @foreach($users as $user)

                <tr class="border-t">

                    <td class="px-4 py-2">

                        {{ $loop->iteration }}

                    </td>

                    <td class="px-4 py-2">

                        {{ $user->name }}

                    </td>

                    <td class="px-4 py-2">

                        {{ $user->email }}

                    </td>

                    <td class="px-4 py-2">

                        {{ ucfirst($user->role) }}

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>
</body>

</html>
