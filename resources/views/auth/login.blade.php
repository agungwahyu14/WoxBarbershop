<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
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
            <!-- Logo -->
            {{-- <div class="text-center mb-8">
                    <img src="{{ asset('images/Logo.jpeg') }}" alt="WOX'S Barbershop Logo"
                        class="h-40 w-auto object-contain mx-auto">

                </div> --}}

            <!-- Login Card -->
            <div class="bg-white bg-opacity-95 p-8 shadow-lg rounded-lg">
                <!-- Logo di dalam card -->
                <div class="text-center mb-6">
                    <img src="{{ asset('images/Logo.png') }}" alt="WOX'S Barbershop Logo"
                        class="h-40 w-auto object-contain mx-auto mb-4">
                </div>

                <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">{{ __('auth.login') }}</h2>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email"
                            class="block text-gray-700 font-medium mb-1">{{ __('auth.email') }}</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            autofocus autocomplete="username"
                            class="w-full px-4 py-3 border border-gray-300 focus:border-[#d4af37] focus:ring-1 focus:ring-[#d4af37] focus:outline-none transition rounded-lg">
                    </div>

                    <div class="mb-4">
                        <label for="password"
                            class="block text-gray-700 font-medium mb-1">{{ __('auth.password') }}</label>
                        <div class="relative">
                            <input id="password" type="password" name="password" required
                                autocomplete="current-password"
                                class="w-full px-4 py-3 pr-12 border border-gray-300 focus:border-[#d4af37] focus:ring-1 focus:ring-[#d4af37] focus:outline-none transition rounded-lg">
                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg id="eyeIcon" class="w-5 h-5 text-gray-400 hover:text-gray-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                <svg id="eyeSlashIcon" class="w-5 h-5 text-gray-400 hover:text-gray-600 hidden"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L8.464 8.464M9.878 9.878l-1.414-1.414M14.12 14.12l1.415 1.414M14.12 14.12L15.535 15.535M14.12 14.12l1.415-1.414M3 3l18 18">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mb-6">
                        {{-- Remember Me - Disabled for now, may be used in the future
                        <label class="inline-flex items-center cursor-pointer group">
                            <input id="remember_me" type="checkbox"
                                class="border-gray-300 text-[#d4af37] focus:ring-[#d4af37] focus:ring-offset-0 rounded transition-colors duration-200"
                                name="remember" value="1">
                            <span
                                class="ml-2 text-sm text-gray-600 group-hover:text-gray-800 transition-colors duration-200">
                                Ingat saya selama 30 hari
                            </span>
                            <svg class="ml-1 w-4 h-4 text-gray-400 group-hover:text-[#d4af37] transition-colors duration-200"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </label>
                        --}}
                        <div></div>
                        @if (Route::has('password.request'))
                            <a class="text-sm text-[#d4af37] hover:underline hover:text-[#111111] transition-colors duration-200"
                                href="{{ route('password.request') }}">
                                {{ __('auth.forgot_password') }}
                            </a>
                        @endif
                    </div>

                    <button type="submit"
                        class="w-full bg-[#d4af37] hover:bg-[#111111] text-white py-3 px-4 font-semibold transition duration-300 rounded-lg">
                        {{ __('auth.login_button') }}
                    </button>

                    <p class="mt-4 text-sm text-center text-gray-600">
                        {{ __('auth.no_account') }}
                        <a href="{{ route('register') }}"
                            class="text-[#d4af37] hover:underline">{{ __('auth.register_here') }}</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeSlashIcon = document.getElementById('eyeSlashIcon');

            if (togglePassword && passwordInput && eyeIcon && eyeSlashIcon) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    eyeIcon.classList.toggle('hidden');
                    eyeSlashIcon.classList.toggle('hidden');
                });
            }

            // Periksa pesan status sukses
            @if (session('status'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('status') }}',
                    confirmButtonColor: '#d4af37',
                    confirmButtonText: 'Oke'
                });
            @endif

            // Periksa error validasi
            @if ($errors->any())
                let errorMessage = '';
                @foreach ($errors->all() as $error)
                    errorMessage += '• {{ $error }}\n';
                @endforeach

                Swal.fire({
                    icon: 'error',
                    title: 'Pendaftaran Gagal!',
                    text: errorMessage,
                    confirmButtonColor: '#d4af37',
                    confirmButtonText: 'Coba Lagi'
                });
            @endif

            // Periksa pesan error umum
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#d4af37',
                    confirmButtonText: 'Oke'
                });
            @endif

            // Periksa pesan informasi
            @if (session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Informasi',
                    text: '{{ session('info') }}',
                    confirmButtonColor: '#d4af37',
                    confirmButtonText: 'Oke'
                });
            @endif

            // Periksa pesan peringatan
            @if (session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: '{{ session('warning') }}',
                    confirmButtonColor: '#d4af37',
                    confirmButtonText: 'Oke'
                });
            @endif

            // Periksa pesan sukses pendaftaran
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Pendaftaran Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#d4af37',
                    confirmButtonText: 'Oke'
                });
            @endif
        });
    </script>
</body>

</html>
