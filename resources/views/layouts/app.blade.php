<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Wox's Barbershop</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Roboto:wght@300;400;500&display=swap');

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: #d4af37;
            transition: width 0.3s;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .feature-card:hover .feature-number {
            opacity: 0.8;
        }

        .menu-item:hover .menu-img {
            transform: scale(1.05);
        }

        .parallax {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        [x-cloak] {
            display: none !important;
        }

        .dropdown-enter-active {
            transition: all 0.2s ease-out;
        }

        .dropdown-leave-active {
            transition: all 0.15s ease-in;
        }

        .dropdown-enter-from,
        .dropdown-leave-to {
            opacity: 0;
            transform: translateY(-10px);
        }
    </style>
</head>

<body class="font-roboto text-gray-800 bg-light">
    @include('layouts.navigation')

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    @include('layouts.footer')

    <!-- Back to Top Button -->
    <button id="back-to-top"
        class="fixed bottom-8 right-8 bg-secondary text-primary p-3 rounded-full shadow-lg opacity-0 invisible transition-all duration-300">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        // Mobile Menu Toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            mobileMenu.classList.toggle('active');

            const icon = mobileMenuButton.querySelector('i');
            if (icon.classList.contains('fa-bars')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });

        // Close mobile menu when clicking on a link
        const mobileMenuLinks = mobileMenu.querySelectorAll('a');
        mobileMenuLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
                mobileMenu.classList.remove('active');
                mobileMenuButton.querySelector('i').classList.remove('fa-times');
                mobileMenuButton.querySelector('i').classList.add('fa-bars');
            });
        });

        // Back to Top Button
        const backToTopButton = document.getElementById('back-to-top');
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.remove('opacity-0', 'invisible');
                backToTopButton.classList.add('opacity-100', 'visible');
            } else {
                backToTopButton.classList.add('opacity-0', 'invisible');
                backToTopButton.classList.remove('opacity-100', 'visible');
            }
        });

        backToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Smooth scrolling for all anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>

    <!-- SweetAlert Script -->
    <script>
        // SweetAlert for Success Messages
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: '{{ __('admin.success_title') }}',
                text: '{{ session('success') }}',
                timer: 4000,
                showConfirmButton: true,
                toast: true,
                position: 'top-end'
            });
        @endif

        // SweetAlert for Booking Success (Special handling)
        @if (session('booking_success'))
            @php $bookingData = session('booking_success'); @endphp
            Swal.fire({
                icon: 'success',
                title: '{{ __('booking.booking_success_title') }}',
                html: `
                <div class="text-left">
                    <p><strong>{{ __('booking.booking_success_name') }}:</strong> {{ $bookingData['name'] }}</p>
                    <p><strong>{{ __('booking.booking_success_queue') }}:</strong> <span class="text-2xl font-bold text-blue-600">{{ $bookingData['queue_number'] }}</span></p>
                    <p><strong>{{ __('booking.booking_success_time') }}:</strong> {{ $bookingData['date_time'] ?? '' }}</p>
                    <p><strong>{{ __('booking.booking_success_service') }}:</strong> {{ $bookingData['service_name'] ?? '' }}</p>
                </div>
                <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <i class="fas fa-info-circle mr-1"></i>
                        {{ __('booking.booking_success_note') }}
                    </p>
                </div>
            `,
                showConfirmButton: true,
                confirmButtonText: 'OK',
                confirmButtonColor: '#3B82F6'
            });
        @endif

        // SweetAlert for Business Hours Error
        @if (session('error') && session('error_type') === 'business_hours')
            Swal.fire({
                icon: 'error',
                title: '{{ __('admin.business_hours_title') }}',
                html: `
                <div class="text-left">
                    <p class="mb-3">{{ session('error') }}</p>
                    <div class="bg-red-50 p-3 rounded-lg">
                        <h4 class="font-semibold text-red-800 mb-2">
                            <i class="fas fa-clock mr-1"></i> {{ __('admin.business_hours_title') }}:
                        </h4>
                        <p class="text-red-700">
                            <strong>{{ __('admin.business_hours_daily') }}</strong><br>
                            <strong>{{ __('admin.business_hours_no_holiday') }}</strong>
                        </p>
                    </div>
                </div>
            `,
                showConfirmButton: true,
                confirmButtonText: '{{ __('admin.choose_other_time') }}',
                confirmButtonColor: '#DC2626'
            });
        @endif

        // SweetAlert for Time Conflict Warning
        @if (session('warning') && session('error_type') === 'time_conflict')
            Swal.fire({
                icon: 'warning',
                title: '{{ __('admin.time_unavailable') }}',
                html: `
                <div class="text-left">
                    <p class="mb-3">{{ session('warning') }}</p>
                    <div class="bg-yellow-50 p-3 rounded-lg">
                        <p class="text-yellow-800">
                            <i class="fas fa-lightbulb mr-1"></i>
                            {{ __('admin.time_conflict_tip') }}
                        </p>
                    </div>
                </div>
            `,
                showConfirmButton: true,
                confirmButtonText: '{{ __('admin.try_again') }}',
                confirmButtonColor: '#F59E0B'
            });
        @endif

        // SweetAlert for General Error Messages
        @if (session('error') && !session('error_type'))
            Swal.fire({
                icon: 'error',
                title: '{{ __('admin.error_title') }}',
                text: '{{ session('error') }}',
                timer: 5000,
                showConfirmButton: true,
                toast: true,
                position: 'top-end'
            });
        @endif

        // SweetAlert for Validation Errors (Laravel Form Request)
        @if (session('validation_error_category'))
            @php
                $errorCategory = session('validation_error_category');
                $validationErrors = session('validation_errors_detail', []);
                $businessLogicErrors = session('business_logic_errors', []);
                $allErrors = array_merge($validationErrors, $businessLogicErrors);
            @endphp

            @if ($errorCategory === 'business_hours')
                Swal.fire({
                    icon: 'error',
                    title: 'Jam Operasional',
                    html: `
                        <div class="text-left">
                            <p class="mb-3">{{ collect($allErrors)->flatten()->first() }}</p>
                            <div class="bg-red-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-red-800 mb-2">
                                    <i class="fas fa-clock mr-1"></i> Jam Operasional Barbershop:
                                </h4>
                                <div class="text-red-700">
                                    <p><strong>Setiap Hari:</strong> 11:00 - 22:00</p>
                                    <p><strong>Tidak Ada Libur</strong> <span class="text-green-600 font-semibold">BUKA SETIAP HARI</span></p>
                                </div>
                                <div class="mt-3 p-2 bg-red-100 rounded text-red-800 text-sm">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Mohon pilih waktu dalam jam operasional kami
                                </div>
                            </div>
                        </div>
                    `,
                    showConfirmButton: true,
                    confirmButtonText: 'Pilih Waktu Lain',
                    confirmButtonColor: '#DC2626',
                    allowOutsideClick: false
                });
            @elseif ($errorCategory === 'closed_day')
                Swal.fire({
                    icon: 'error',
                    title: 'Hari Libur',
                    html: `
                        <div class="text-left">
                            <p class="mb-3">{{ collect($allErrors)->flatten()->first() }}</p>
                            <div class="bg-red-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-red-800 mb-2">
                                    <i class="fas fa-calendar-times mr-1"></i> Jadwal Operasional:
                                </h4>
                                <div class="text-red-700">
                                    <p><strong>Hari Buka:</strong> Senin - Sabtu</p>
                                    <p><strong>Hari Libur:</strong> Minggu</p>
                                </div>
                                <div class="mt-3 p-2 bg-blue-50 rounded text-blue-800 text-sm">
                                    <i class="fas fa-lightbulb mr-1"></i>
                                    <strong>Saran:</strong> Pilih hari Senin - Sabtu untuk booking Anda
                                </div>
                            </div>
                        </div>
                    `,
                    showConfirmButton: true,
                    confirmButtonText: 'Pilih Hari Lain',
                    confirmButtonColor: '#DC2626',
                    allowOutsideClick: false
                });
            @elseif ($errorCategory === 'past_date')
                Swal.fire({
                    icon: 'warning',
                    title: 'Waktu Tidak Valid',
                    html: `
                        <div class="text-left">
                            <p class="mb-3">{{ collect($allErrors)->flatten()->first() }}</p>
                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <p class="text-yellow-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Pastikan Anda memilih tanggal dan waktu di masa depan
                                </p>
                            </div>
                        </div>
                    `,
                    showConfirmButton: true,
                    confirmButtonText: 'Pilih Waktu Baru',
                    confirmButtonColor: '#F59E0B'
                });
            @elseif ($errorCategory === 'selection_error')
                Swal.fire({
                    icon: 'info',
                    title: 'Pilihan Tidak Lengkap',
                    html: `
                        <div class="text-left">
                            @foreach ($allErrors as $field => $errors)
                                @foreach ($errors as $error)
                                    <p class="mb-2">• {{ $error }}</p>
                                @endforeach
                            @endforeach
                            <div class="bg-blue-50 p-3 rounded-lg mt-3">
                                <p class="text-blue-800 text-sm">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Pastikan semua field wajib sudah dipilih
                                </p>
                            </div>
                        </div>
                    `,
                    showConfirmButton: true,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3B82F6'
                });
            @else
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    html: `
                        <div class="text-left">
                            @foreach ($allErrors as $field => $errors)
                                @foreach ($errors as $error)
                                    <p class="mb-2">• {{ $error }}</p>
                                @endforeach
                            @endforeach
                        </div>
                    `,
                    showConfirmButton: true,
                    confirmButtonText: 'Perbaiki',
                    confirmButtonColor: '#DC2626'
                });
            @endif
        @endif

        // SweetAlert for Regular Laravel Validation Errors
        @if ($errors->any() && !session('validation_error_category'))
            @php
                $firstError = $errors->first();
                $allErrorMessages = $errors->all();
            @endphp
            Swal.fire({
                icon: 'error',
                title: 'Form Validation Error',
                html: `
                    <div class="text-left">
                        @foreach ($allErrorMessages as $error)
                            <p class="mb-2 text-red-600">• {{ $error }}</p>
                        @endforeach
                    </div>
                `,
                showConfirmButton: true,
                confirmButtonText: 'Perbaiki Form',
                confirmButtonColor: '#DC2626'
            });
        @endif

        // SweetAlert for Warning Messages
        @if (session('warning') && !session('error_type'))
            Swal.fire({
                icon: 'warning',
                title: 'Warning!',
                text: '{{ session('warning') }}',
                timer: 4000,
                showConfirmButton: true,
                toast: true,
                position: 'top-end'
            });
        @endif

        // SweetAlert for Info Messages
        @if (session('info'))
            Swal.fire({
                icon: 'info',
                title: 'Info',
                text: '{{ session('info') }}',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        @endif

        // SweetAlert for Status Messages (profile updated, etc)
        @if (session('status'))
            Swal.fire({
                icon: 'success',
                title: 'Updated!',
                text: 'Profile updated successfully!',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        @endif
    </script>

    @stack('scripts')
</body>

</html>
