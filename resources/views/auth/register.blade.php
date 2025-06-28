<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
    @vite('resources/css/app.css')

</head>

<body class="relative min-h-screen bg-gray-900">

    <!-- Background image with overlay -->
    <div class="absolute inset-0">
        <img src="{{ asset('images/hero.jpg') }}" alt="Background" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black opacity-60"></div> {{-- Overlay gelap --}}
    </div>

    <!-- Form container -->
    <div class="relative z-10 flex items-center justify-center min-h-screen">
        <div class="bg-white bg-opacity-95 p-8  shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Daftar Akun</h2>

            @if ($errors->any())
                <div class="bg-red-100 text-red-600 p-4 rounded mb-4 text-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-medium mb-1">Nama Lengkap</label>
                    <input type="text" name="name" id="name"
                        class="w-full px-4 py-3 border border-gray-300 focus:border-[#d4af37] focus:ring-1 focus:ring-[#d4af37] focus:outline-none transition"
                        value="{{ old('name') }}" required autofocus>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
                    <input type="email" name="email" id="email"
                        class="w-full px-4 py-3 border border-gray-300 focus:border-[#d4af37] focus:ring-1 focus:ring-[#d4af37] focus:outline-none transition"
                        value="{{ old('email') }}" required>
                </div>

                <div class="mb-4">
                    <label for="no_telepon" class="block text-gray-700 font-medium mb-1">No Telepon</label>
                    <input type="text" name="no_telepon" id="no_telepon"
                        class="w-full px-4 py-3 border border-gray-300 focus:border-[#d4af37] focus:ring-1 focus:ring-[#d4af37] focus:outline-none transition"
                        value="{{ old('no_telepon') }}" required>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
                    <input type="password" name="password" id="password"
                        class="w-full px-4 py-3 border border-gray-300 focus:border-[#d4af37] focus:ring-1 focus:ring-[#d4af37] focus:outline-none transition"
                        required>
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-gray-700 font-medium mb-1">Konfirmasi Password
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full px-4 py-3 border border-gray-300 focus:border-[#d4af37] focus:ring-1 focus:ring-[#d4af37] focus:outline-none transition"
                        required>
                </div>

                <button type="submit"
                    class="w-full bg-[#d4af37] hover:bg-[#111111] text-white py-3 px-4 font-semibold transition duration-300 ">
                    Daftar
                </button>

                <p class="mt-4 text-sm text-center text-gray-600">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-[#d4af37] hover:underline">Login di sini</a>
                </p>
            </form>
        </div>
    </div>

</body>

</html>
