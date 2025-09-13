<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forgot Password - WOX Barbershop</title>
    @vite('resources/css/app.css')
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="relative min-h-screen bg-gray-900">

    <!-- Background image with overlay -->
    <div class="absolute inset-0">
        <img src="{{ asset('images/hero.jpg') }}" alt="Background" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black opacity-60"></div>
    </div>

    <!-- Form container -->
    <div class="relative z-10 flex items-center justify-center min-h-screen px-4">
        <div class="w-full max-w-md">
            <!-- Forgot Password Card -->
            <div class="bg-white bg-opacity-95 p-8 shadow-lg rounded-lg">
                <!-- Logo di dalam card -->
                <div class="text-center mb-6">
                    <img src="{{ asset('images/Logo.png') }}" alt="WOX'S Barbershop Logo"
                        class="h-20 w-auto object-contain mx-auto mb-4">
                </div>

                <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">Reset Password</h2>
                <p class="text-center text-gray-600 text-sm mb-6">
                    Lupa password? Masukkan email Anda dan kami akan mengirimkan link reset password.
                </p>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-medium mb-1">Email Address</label>
                        <div class="relative">
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                autofocus autocomplete="email"
                                class="w-full px-4 py-3 pl-12 border border-gray-300 focus:border-[#d4af37] focus:ring-1 focus:ring-[#d4af37] focus:outline-none transition rounded-lg @error('email') border-red-500 @enderror"
                                placeholder="Masukkan email Anda">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-[#d4af37] hover:bg-[#111111] text-white py-3 px-4 font-semibold transition duration-300 rounded-lg mb-4">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Kirim Link Reset Password
                    </button>

                    <div class="text-center">
                        <p class="text-sm text-gray-600">
                            Ingat password Anda?
                            <a href="{{ route('login') }}" class="text-[#d4af37] hover:underline font-medium">
                                Kembali ke Login
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Additional Info -->
            <div class="mt-6 text-center text-white">
                <p class="text-sm opacity-75">
                    <i class="fas fa-info-circle mr-1"></i>
                    Link reset password akan dikirim ke email Anda dalam beberapa menit.
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle success message
            @if (session('status'))
                Swal.fire({
                    icon: 'success',
                    title: 'Email Terkirim!',
                    text: '{{ session('status') }}',
                    confirmButtonColor: '#d4af37',
                    confirmButtonText: 'Oke'
                });
            @endif

            // Handle validation errors
            @if ($errors->any())
                let errorMessage = '';
                @foreach ($errors->all() as $error)
                    errorMessage += '• {{ $error }}\n';
                @endforeach

                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    text: errorMessage,
                    confirmButtonColor: '#d4af37',
                    confirmButtonText: 'Coba Lagi'
                });
            @endif
        });
    </script>
</body>

</html>
