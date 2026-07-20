<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Stockify Dashboard</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

    <script>
        // Dark mode locked — always dark
        document.documentElement.classList.add('dark');
    </script>

</head>

<body class="bg-gray-900">

    <x-navbar-dashboard />

    <div class="flex pt-16 overflow-hidden bg-gray-900">

        @if(auth()->user()->role === 'manager')
            <x-sidebar.manager-sidebar />
        @elseif(auth()->user()->role === 'staff')
            <x-sidebar.staff-sidebar />
        @else
            <x-sidebar.admin-sidebar />
        @endif

        <div id="main-content"
            class="relative min-h-screen w-full overflow-y-auto bg-gray-900 lg:ml-64">

            <main class="min-h-screen p-4 sm:p-6 lg:p-8 bg-gray-900">

                {{-- Access denied alert --}}
                @if(session('error'))
                <div class="mb-4 rounded-xl border border-red-700 bg-red-900/40 px-4 py-3 flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-red-300 text-sm">{{ session('error') }}</span>
                </div>
                @endif

                @yield('content')

            </main>

            <x-footer-dashboard />

        </div>

    </div>

    {{-- SweetAlert Success --}}
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'OK'
            });
        });
    </script>
    @endif

    {{-- SweetAlert Delete --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            document.querySelectorAll('.delete-form').forEach(form => {

                form.addEventListener('submit', function(e) {

                    e.preventDefault();

                    Swal.fire({
                        title: 'Hapus Data?',
                        text: 'Data yang dihapus tidak dapat dikembalikan!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal'
                    }).then((result) => {

                        if(result.isConfirmed){
                            form.submit();
                        }

                    });

                });

            });

        });
    </script>

</body>

</html>
