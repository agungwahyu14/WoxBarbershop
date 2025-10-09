<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password - WOX Barbershop</title>
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
            <!-- Reset Password Card -->
            <div class="bg-white bg-opacity-95 p-8 shadow-lg rounded-lg">
                <!-- Logo di dalam card -->
                <div class="text-center mb-6">
                    <img src="{{ asset('images/Logo.png') }}" alt="WOX'S Barbershop Logo"
                        class="h-20 w-auto object-contain mx-auto mb-4">
                </div>

                <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">{{ __('auth.create_new_password') }}</h2>
                <p class="text-center text-gray-600 text-sm mb-6">
                    {{ __('auth.enter_new_password_description') }}
                </p>

                <form method="POST" action="{{ route('password.store') }}" id="resetPasswordForm">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email Address (Hidden) -->
                    <input type="hidden" name="email" value="{{ old('email', $request->email) }}">

                    <!-- Display Email (Read-only) -->
                    <div class="mb-4">
                        <label for="email_display"
                            class="block text-gray-700 font-medium mb-1">{{ __('auth.email') }}</label>
                        <div class="relative">
                            <input id="email_display" type="email" value="{{ old('email', $request->email) }}"
                                readonly
                                class="w-full px-4 py-3 pl-12 border border-gray-300 bg-gray-100 rounded-lg cursor-not-allowed">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- New Password -->
                    <div class="mb-4">
                        <label for="password"
                            class="block text-gray-700 font-medium mb-1">{{ __('auth.new_password') }}</label>
                        <div class="relative">
                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                class="w-full px-4 py-3 pl-12 pr-12 border border-gray-300 focus:border-[#d4af37] focus:ring-1 focus:ring-[#d4af37] focus:outline-none transition rounded-lg @error('password') border-red-500 @enderror"
                                placeholder="Masukkan password baru">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye text-gray-400 hover:text-gray-600" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="mt-1 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Minimal 8 karakter, kombinasi huruf dan angka
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label for="password_confirmation"
                            class="block text-gray-700 font-medium mb-1">{{ __('auth.password_confirmation') }}</label>
                        <div class="relative">
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                autocomplete="new-password"
                                class="w-full px-4 py-3 pl-12 pr-12 border border-gray-300 focus:border-[#d4af37] focus:ring-1 focus:ring-[#d4af37] focus:outline-none transition rounded-lg @error('password_confirmation') border-red-500 @enderror"
                                placeholder="Ulangi password baru">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <button type="button" id="toggleConfirmPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye text-gray-400 hover:text-gray-600"
                                    id="toggleConfirmPasswordIcon"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Strength Indicator -->
                    <div class="mb-4">
                        <div class="text-xs text-gray-500 mb-2">{{ __('auth.password_strength') }}:</div>
                        <div class="flex space-x-1">
                            <div class="h-2 flex-1 bg-gray-200 rounded-full" id="strength-bar-1"></div>
                            <div class="h-2 flex-1 bg-gray-200 rounded-full" id="strength-bar-2"></div>
                            <div class="h-2 flex-1 bg-gray-200 rounded-full" id="strength-bar-3"></div>
                            <div class="h-2 flex-1 bg-gray-200 rounded-full" id="strength-bar-4"></div>
                        </div>
                        <div class="text-xs mt-1" id="strength-text">{{ __('auth.weak') }}</div>
                    </div>

                    <button type="submit"
                        class="w-full bg-[#d4af37] hover:bg-[#111111] text-white py-3 px-4 font-semibold transition duration-300 rounded-lg mb-4">
                        <i class="fas fa-key mr-2"></i>
                        {{ __('auth.reset_password_button') }}
                    </button>

                    <div class="text-center">
                        <p class="text-sm text-gray-600">
                            {{ __('auth.remember_password') }}
                            <a href="{{ route('login') }}" class="text-[#d4af37] hover:underline font-medium">
                                {{ __('auth.back_to_login') }}
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const togglePasswordIcon = document.getElementById('togglePasswordIcon');

            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const toggleConfirmPasswordIcon = document.getElementById('toggleConfirmPasswordIcon');

            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                togglePasswordIcon.classList.toggle('fa-eye');
                togglePasswordIcon.classList.toggle('fa-eye-slash');
            });

            toggleConfirmPassword.addEventListener('click', function() {
                const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmPasswordInput.setAttribute('type', type);
                toggleConfirmPasswordIcon.classList.toggle('fa-eye');
                toggleConfirmPasswordIcon.classList.toggle('fa-eye-slash');
            });

            // Password strength checker
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                const strength = calculatePasswordStrength(password);
                updatePasswordStrengthUI(strength);
            });

            function calculatePasswordStrength(password) {
                let score = 0;
                if (password.length >= 8) score++;
                if (/[a-z]/.test(password)) score++;
                if (/[A-Z]/.test(password)) score++;
                if (/[0-9]/.test(password)) score++;
                if (/[^A-Za-z0-9]/.test(password)) score++;
                return Math.min(score, 4);
            }

            function updatePasswordStrengthUI(strength) {
                const bars = document.querySelectorAll('[id^="strength-bar-"]');
                const strengthText = document.getElementById('strength-text');

                bars.forEach((bar, index) => {
                    bar.className = 'h-2 flex-1 rounded-full';
                    if (index < strength) {
                        if (strength <= 1) bar.classList.add('bg-red-500');
                        else if (strength <= 2) bar.classList.add('bg-orange-500');
                        else if (strength <= 3) bar.classList.add('bg-yellow-500');
                        else bar.classList.add('bg-green-500');
                    } else {
                        bar.classList.add('bg-gray-200');
                    }
                });

                const texts = ['Lemah', 'Cukup', 'Baik', 'Kuat'];
                const colors = ['text-red-500', 'text-orange-500', 'text-yellow-500', 'text-green-500'];

                strengthText.textContent = texts[Math.max(0, strength - 1)] || 'Lemah';
                strengthText.className = 'text-xs mt-1 ' + (colors[Math.max(0, strength - 1)] || 'text-red-500');
            }

            // Form validation
            document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('password_confirmation').value;

                if (password !== confirmPassword) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Password Tidak Cocok!',
                        text: 'Password dan konfirmasi password tidak sama.',
                        confirmButtonColor: '#d4af37',
                        confirmButtonText: 'Oke'
                    });
                    return;
                }

                if (password.length < 8) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Password Terlalu Pendek!',
                        text: 'Password harus minimal 8 karakter.',
                        confirmButtonColor: '#d4af37',
                        confirmButtonText: 'Oke'
                    });
                    return;
                }
            });

            // Handle validation errors
            @if ($errors->any())
                let errorMessage = '';
                @foreach ($errors->all() as $error)
                    errorMessage += '• {{ $error }}\n';
                @endforeach

                Swal.fire({
                    icon: 'error',
                    title: 'Reset Password Gagal!',
                    text: errorMessage,
                    confirmButtonColor: '#d4af37',
                    confirmButtonText: 'Coba Lagi'
                });
            @endif

            // Handle success message
            @if (session('status'))
                Swal.fire({
                    icon: 'success',
                    title: 'Password Berhasil Direset!',
                    text: '{{ session('status') }}',
                    confirmButtonColor: '#d4af37',
                    confirmButtonText: 'Login Sekarang'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('login') }}';
                    }
                });
            @endif
        });
    </script>
</body>

</html>
