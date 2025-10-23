<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.verify_email_title') }} - WOX Barbershop</title>
    @vite('resources/css/app.css') <!-- Your TailwindCSS (or custom CSS) file -->

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Add any other meta or external resources here -->
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
            <!-- Verification Card -->
            <div class="bg-white bg-opacity-95 p-8 shadow-lg rounded-lg">
                <!-- Icon and Title -->
                <div class="text-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-[#d4af37]" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 12H8m0 0l4-4m-4 4l4 4" />
                    </svg>
                    <h2 class="mt-4 text-2xl font-bold text-center text-gray-800 mb-2">
                        {{ __('auth.verify_email_title') }}</h2>
                    <p class="text-center text-gray-600 text-sm mb-6">{{ __('auth.verify_email_message') }}</p>
                </div>

                <!-- Session Status -->
                @if (session('status') == 'verification-link-sent')
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ __('auth.verification_link_sent') }}
                    </div>
                @endif

                <!-- Resend Verification -->
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit"
                        class="w-full bg-[#d4af37] hover:bg-[#111111] text-white py-3 px-4 font-semibold transition duration-300 rounded-lg mb-4">
                        🔄 {{ __('auth.resend_verification') }}
                    </button>
                </form>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full bg-gray-100 text-gray-700 py-3 rounded-xl shadow-md hover:bg-gray-200 transition duration-200 font-semibold">
                        🚪 {{ __('auth.logout') }}
                    </button>
                </form>
            </div>

            <!-- Additional Info -->
            {{-- <div class="mt-6 text-center text-white">
                <p class="text-sm opacity-75">
                    <i class="fas fa-info-circle mr-1"></i>
                    {{ __('auth.reset_link_info') }}
                </p>
            </div> --}}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Translation variables
            const translations = {
                email_sent: @json(__('auth.email_sent')),
                error_occurred: @json(__('auth.error_occurred')),
                ok: @json(__('auth.ok')),
                try_again: @json(__('auth.try_again'))
            };

            // Handle success message
            @if (session('status'))
                Swal.fire({
                    icon: 'success',
                    title: translations.email_sent,
                    text: '{{ session('status') }}',
                    confirmButtonColor: '#d4af37',
                    confirmButtonText: translations.ok
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
                    title: translations.error_occurred,
                    text: errorMessage,
                    confirmButtonColor: '#d4af37',
                    confirmButtonText: translations.try_again
                });
            @endif
        });
    </script>
</body>

</html>
