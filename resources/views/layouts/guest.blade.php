<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wox's Barbershop</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <!-- Sertakan semua CSS dan JS yang diperlukan -->
    @include('layouts.head')
</head>

<body class="font-roboto text-gray-800 bg-light">
    @include('layouts.navigation')

    <main>
        @yield('content')
    </main>

    @include('layouts.footer')

    @include('layouts.scripts')
</body>

</html>
