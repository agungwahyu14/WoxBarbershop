<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    @vite('resources/css/app.css')
</head>

<body class="relative min-h-screen bg-gray-900">

    <!-- Background image with overlay -->
    <div class="absolute inset-0">
        <img src="{{ asset('images/hero.jpg') }}" alt="Background" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black opacity-60"></div>
    </div>

    <!-- Form container -->
    <div class="relative z-10 flex items-center justify-center min-h-screen">
        <div class="bg-white bg-opacity-95 p-8 shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Login</h2>

            @if (session('status'))
                <div class="bg-green-100 text-green-700 p-4 rounded mb-4 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 text-red-600 p-4 rounded mb-4 text-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        autocomplete="username"
                        class="w-full px-4 py-3 border border-gray-300 focus:border-[#d4af37] focus:ring-1 focus:ring-[#d4af37] focus:outline-none transition">
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="w-full px-4 py-3 border border-gray-300 focus:border-[#d4af37] focus:ring-1 focus:ring-[#d4af37] focus:outline-none transition">
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="border-gray-300 text-[#d4af37] focus:ring-[#d4af37]" name="remember">
                        <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a class="text-sm text-[#d4af37] hover:underline" href="{{ route('password.request') }}">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <button type="submit"
                    class="w-full bg-[#d4af37] hover:bg-[#111111] text-white py-3 px-4 font-semibold transition duration-300">
                    Login
                </button>

                <p class="mt-4 text-sm text-center text-gray-600">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-[#d4af37] hover:underline">Daftar di sini</a>
                </p>
            </form>
        </div>
    </div>
</body>

</html>
