<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wox's Barbershop</title>
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
