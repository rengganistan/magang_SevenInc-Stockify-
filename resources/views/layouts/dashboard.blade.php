<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Stockify Dashboard</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <script>
        if (
            localStorage.getItem('color-theme') === 'dark' ||
            (!('color-theme' in localStorage) &&
                window.matchMedia('(prefers-color-scheme: dark)').matches)
        ) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

</head>

<body class="bg-gray-900">

    <x-navbar-dashboard />

    <div class="flex pt-16 overflow-hidden bg-gray-900">

        <x-sidebar.admin-sidebar />

        <div
            id="main-content"
            class="relative w-full min-h-screen overflow-y-auto bg-gray-900 lg:ml-64">

            <main class="p-6">

                @yield('content')

            </main>

            <x-footer-dashboard />

        </div>

    </div>

</body>

</html>
