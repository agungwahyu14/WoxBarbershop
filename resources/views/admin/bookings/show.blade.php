@extends('admin.layouts.app')

@section('title', __('admin.booking_details') . ' - #' . $booking->id)
@section('meta_description', 'Detailed booking information for administrative management')

@section('content')
    <div class="is-hero-bar">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold mb-2">
                    <i class="fas fa-calendar-check mr-3"></i>{{ __('admin.booking_details') }}
                </h1>
                <p class="text-lg">
                    {{ __('admin.manage_booking') }} #{{ $booking->id }} -
                    {{ $booking->user->name ?? __('admin.unknown_customer') }}
                </p>
            </div>
            <div class="flex items-center space-x-4 mt-4 md:mt-0">
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                        'confirmed' => 'bg-blue-100 text-blue-800 border-blue-200',
                        'in_progress' => 'bg-orange-100 text-orange-800 border-orange-200',
                        'completed' => 'bg-green-100 text-green-800 border-green-200',
                        'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                    ];
                    $statusIcons = [
                        'pending' => 'fas fa-clock',
                        'confirmed' => 'fas fa-check',
                        'in_progress' => 'fas fa-cut',
                        'completed' => 'fas fa-check-circle',
                        'cancelled' => 'fas fa-times-circle',
                    ];
                @endphp

                <span
                    class="glass-effect px-4 py-2 rounded-lg border {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                    <i class="{{ $statusIcons[$booking->status] ?? 'fas fa-question-circle' }} mr-2"></i>
                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                </span>
            </div>
        </div>
    </div>

    <section class="section main-section">
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <!-- Main Content -->
            <div class="xl:col-span-2 space-y-6">

                <!-- Booking Overview -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-info-circle mr-3 text-blue-600"></i>
                            {{ __('admin.booking_overview') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Booking Info -->
                            <div class="space-y-4">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-green-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ __('admin.appointment') }}</h3>
                                        <p class="text-gray-600 dark:text-gray-300">
                                            {{ \Carbon\Carbon::parse($booking->date_time)->format('l, M d, Y') }}</p>
                                        <p class="text-lg font-bold text-green-600">
                                            {{ \Carbon\Carbon::parse($booking->date_time)->format('H:i A') }}</p>
                                    </div>
                                </div>

                                @if ($queuePosition)
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-users text-blue-600"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                {{ __('admin.queue_position') }}
                                            </h3>
                                            <p class="text-2xl font-bold text-blue-600">#{{ $queuePosition }}</p>
                                            @if ($estimatedWaitTime && $estimatedWaitTime > 0)
                                                <p class="text-sm text-gray-500">Est. wait: {{ $estimatedWaitTime }} min
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Total Amount -->
                            <div class="space-y-4">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-dollar-sign text-green-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ __('admin.total_amount') }}</h3>
                                        <p class="text-2xl font-bold text-green-600">
                                            Rp
                                            {{ number_format($booking->total_price ?? ($booking->service->price ?? 0), 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-clock text-purple-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ __('admin.duration') }}</h3>
                                        <p class="text-lg font-bold text-purple-600">
                                            {{ $booking->service->duration ?? 30 }} {{ __('admin.minutes') }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Service & Hairstyle Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Service Details -->
                    @if ($booking->service)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                            <div
                                class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                                    <i class="fas fa-cut mr-3 text-green-600"></i>
                                    {{ __('admin.service_details') }}
                                </h3>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    <div>
                                        <h4 class="text-xl font-semibold text-gray-900 dark:text-white">
                                            {{ $booking->service->name }}</h4>
                                        <p class="text-gray-600 dark:text-gray-300 mt-2">
                                            {{ $booking->service->description ?? 'Professional barbershop service' }}</p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <span class="text-gray-600 dark:text-gray-400">{{ __('admin.price') }}:</span>
                                            <p class="font-bold text-green-600">Rp
                                                {{ number_format($booking->service->price, 0, ',', '.') }}</p>
                                        </div>
                                        <div>
                                            <span
                                                class="text-gray-600 dark:text-gray-400">{{ __('admin.duration') }}:</span>
                                            <p class="font-bold text-blue-600">{{ $booking->service->duration ?? 30 }} min
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Hairstyle Details -->
                    @if ($booking->hairstyle)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                            <div
                                class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                                    <i class="fas fa-user-tie mr-3 text-purple-600"></i>
                                    {{ __('admin.hairstyle_details') }}
                                </h3>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    <div>
                                        <h4 class="text-xl font-semibold text-gray-900 dark:text-white">
                                            {{ $booking->hairstyle->name }}</h4>
                                        <p class="text-gray-600 dark:text-gray-300 mt-2">
                                            {{ $booking->hairstyle->description ?? 'Classic hairstyle' }}</p>
                                    </div>
                                    <div class="grid grid-cols-1 gap-3">

                                        {{-- Bentuk Kepala --}}
                                        @if ($booking->hairstyle && $booking->hairstyle->bentuk_kepala->isNotEmpty())
                                            <div class="flex justify-between">
                                                <span
                                                    class="text-gray-600 dark:text-gray-400">{{ __('admin.face_shape') }}:</span>
                                                <span class="font-medium text-gray-900 dark:text-white">
                                                    {{ $booking->hairstyle->bentuk_kepala->pluck('nama')->join(', ') }}
                                                </span>
                                            </div>
                                        @endif

                                        {{-- Tipe Rambut --}}
                                        @if ($booking->hairstyle && $booking->hairstyle->tipe_rambut->isNotEmpty())
                                            <div class="flex justify-between">
                                                <span
                                                    class="text-gray-600 dark:text-gray-400">{{ __('admin.hair_type') }}:</span>
                                                <span class="font-medium text-gray-900 dark:text-white">
                                                    {{ $booking->hairstyle->tipe_rambut->pluck('nama')->join(', ') }}
                                                </span>
                                            </div>
                                        @endif

                                        {{-- Style Preference --}}
                                        @if ($booking->hairstyle && $booking->hairstyle->style_preference->isNotEmpty())
                                            <div class="flex justify-between">
                                                <span
                                                    class="text-gray-600 dark:text-gray-400">{{ __('admin.style_preference') }}:</span>
                                                <span class="font-medium text-gray-900 dark:text-white">
                                                    {{ $booking->hairstyle->style_preference->pluck('nama')->join(', ') }}
                                                </span>
                                            </div>
                                        @endif

                                    </div>

                                </div>
                            </div>
                        </div>
                    @endif

                </div>

                <!-- Payment Information -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-credit-card mr-3 text-blue-600"></i>
                            {{ __('admin.payment_information') }}
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Payment Method -->
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('admin.payment_method') }}:</span>
                                <div class="flex items-center space-x-2">
                                    @if($booking->payment_method === 'cash')
                                        <i class="fas fa-money-bill-wave text-green-500"></i>
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ __('admin.payment_cash') }}</span>
                                    @else
                                        <i class="fas fa-university text-blue-500"></i>
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ __('admin.payment_bank_transfer') }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Transaction Status -->
                            @if($booking->transaction)
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">{{ __('admin.transaction_status') }}:</span>
                                    @php
                                        $transactionColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'settlement' => 'bg-green-100 text-green-800',
                                            'capture' => 'bg-green-100 text-green-800',
                                            'cancel' => 'bg-red-100 text-red-800',
                                            'expire' => 'bg-gray-100 text-gray-800',
                                        ];
                                        $statusColor = $transactionColors[$booking->transaction->transaction_status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                                        {{ ucfirst($booking->transaction->transaction_status) }}
                                    </span>
                                </div>

                                <!-- VA Number for Bank Transfer -->
                                @if($booking->payment_method === 'bank')
                                    @if($booking->transaction->va_number)
                                        {{-- VA Number tersedia --}}
                                        <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                            <div class="flex items-start space-x-3">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-2">
                                                        {{ __('admin.va_number_title') }}
                                                    </h4>
                                                    <div class="space-y-2">
                                                        <div>
                                                            <span class="text-xs text-gray-600 dark:text-gray-400">{{ __('admin.va_bank') }}:</span>
                                                            <p class="font-semibold text-gray-900 dark:text-white">
                                                                {{ strtoupper($booking->transaction->bank ?? 'N/A') }}
                                                            </p>
                                                        </div>
                                                        <div>
                                                            <span class="text-xs text-gray-600 dark:text-gray-400">{{ __('admin.va_number_label') }}:</span>
                                                            <div class="flex items-center space-x-2 mt-1">
                                                                <code class="px-3 py-2 bg-white dark:bg-gray-800 rounded border border-gray-300 dark:border-gray-600 font-mono text-lg font-bold text-blue-600">
                                                                    {{ $booking->transaction->va_number }}
                                                                </code>
                                                                <button onclick="copyVA('{{ $booking->transaction->va_number }}')" 
                                                                    class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded transition-colors">
                                                                    <i class="fas fa-copy"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        {{-- VA Number belum tersedia --}}
                                        <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                                            <div class="flex items-start space-x-3">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-2">
                                                        {{ __('admin.va_processing_title') }}
                                                    </h4>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                                        {{ __('admin.va_processing_message') }}
                                                    </p>
                                                    <div class="flex items-center space-x-2">
                                                        <button onclick="window.location.reload()" 
                                                            class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded transition-colors text-sm">
                                                            <i class="fas fa-sync mr-2"></i>
                                                            {{ __('admin.refresh_page') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @else
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Status Transaksi:</span>
                                    <span class="text-gray-500">{{ __('admin.no_transaction') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Transaction History -->

            </div>

            @if ($booking->user)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-user mr-3 text-blue-600"></i>
                            {{ __('admin.customer_information') }}
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center space-x-4 mb-4">
                            <div
                                class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white text-xl font-bold">
                                {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $booking->user->name }}</h4>
                                <p class="text-gray-600 dark:text-gray-400">{{ $booking->user->email }}</p>
                                @if ($booking->user->no_telepon)
                                    <p class="text-gray-600 dark:text-gray-400">{{ $booking->user->no_telepon }}</p>
                                @endif
                            </div>
                        </div>


                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-cogs mr-3 text-green-600"></i>
                        {{ __('admin.actions_column') }}
                    </h3>
                </div>
                <div class="p-6 space-y-3">

                    <!-- Status Update Buttons -->
                    @if ($booking->status === 'pending')
                        <button onclick="confirmBooking({{ $booking->id }})"
                            class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-check mr-2"></i>
                            {{ __('admin.confirm_booking') }}
                        </button>
                    @elseif($booking->status === 'confirmed')
                        <button onclick="startService({{ $booking->id }})"
                            class="w-full bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors">
                            <i class="fas fa-play mr-2"></i>
                            {{ __('admin.start_service') }}
                        </button>
                    @elseif($booking->status === 'in_progress')
                        <button onclick="completeService({{ $booking->id }})"
                            class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ __('admin.complete_service') }}
                        </button>
                    @endif

                    @if ($canCancel)
                        <button onclick="cancelBooking({{ $booking->id }})"
                            class="w-full border border-red-300 text-red-700 px-4 py-2 rounded-lg hover:bg-red-50 transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            {{ __('admin.cancel_booking') }}
                        </button>
                    @endif


                    <a href="{{ route('admin.bookings.index') }}"
                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        {{ __('admin.back_to_bookings') }}
                    </a>

                </div>
            </div>
    </section>
@endsection

@push('scripts')
    <script>
        // ✅ Translation variables (now working properly with Indonesian locale)
        const success = '{{ __('admin.success') }}';
        const error = '{{ __('admin.error') }}';
        const deleted = '{{ __('admin.deleted') }}';
        const areYouSure = '{{ __('admin.are_you_sure') }}';
        const somethingWentWrong = '{{ __('admin.something_went_wrong') }}';
        const processing = '{{ __('admin.processing') }}';
        const search = '{{ __('admin.search') }}';
        const lengthMenu = '{{ __('admin.show_entries') }}';
        const info = '{{ __('admin.showing_entries') }}';
        const infoEmpty = '{{ __('admin.showing_empty') }}';
        const infoFiltered = '{{ __('admin.filtered_entries') }}';
        const noMatchingBookings = '{{ __('admin.no_matching_bookings') }}';
        const noBookingsAvailable = '{{ __('admin.no_bookings_available') }}';
        const loadingBookings = '{{ __('admin.loading_bookings') }}';
        const firstPage = '{{ __('admin.first') }}';
        const lastPage = '{{ __('admin.last') }}';
        const nextPage = '{{ __('admin.next') }}';
        const previousPage = '{{ __('admin.previous') }}';
        const confirm = '{{ __('admin.confirm') }}';
        const cancel = '{{ __('admin.cancel') }}';
        const yes = '{{ __('admin.yes') }}';
        const no = '{{ __('admin.no') }}';
        const confirmBookingText = '{{ __('admin.confirm_booking') }}';
        const startServiceText = '{{ __('admin.start_service') }}';
        const completeServiceText = '{{ __('admin.complete_service') }}';
        const cancelBookingText = '{{ __('admin.cancel_booking') }}';
        const confirmBookingMessage = '{{ __('admin.confirm_booking_message') }}';
        const startServiceMessage = '{{ __('admin.start_service_message') }}';
        const completeServiceMessage = '{{ __('admin.complete_service_message') }}';
        const cancelBookingMessage = '{{ __('admin.cancel_booking_message') }}';
        const errorOccurred = '{{ __('admin.error_occurred') }}';

        // Booking status update functions
        function confirmBooking(bookingId) {
            updateBookingStatus(bookingId, 'confirmed', confirmBookingMessage);
        }

        function startService(bookingId) {
            updateBookingStatus(bookingId, 'in_progress', startServiceMessage);
        }

        function completeService(bookingId) {
            updateBookingStatus(bookingId, 'completed', completeServiceMessage);
        }

        function cancelBooking(bookingId) {
            updateBookingStatus(bookingId, 'cancelled', cancelBookingMessage);
        }

        function updateBookingStatus(bookingId, status, action) {
            Swal.fire({
                title: confirm,
                text: action,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d4af37',
                cancelButtonColor: '#aaa',
                confirmButtonText: yes,
                cancelButtonText: cancel
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route('admin.bookings.updateStatus', ':bookingId') }}'.replace(':bookingId',
                            bookingId), {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                status: status
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showNotification('success', success, data.message);
                                location.reload();
                            } else {
                                showNotification('error', error, data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showNotification('error', error, errorOccurred);
                        });
                }
            });
        }

        // Copy VA Number to clipboard
        function copyVA(vaNumber) {
            navigator.clipboard.writeText(vaNumber).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ __('admin.va_copied_success') }}',
                    timer: 2000,
                    showConfirmButton: false
                });
            }).catch(err => {
                console.error('Failed to copy:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ __('admin.va_copy_failed') }}',
                    confirmButtonColor: '#d4af37'
                });
            });
        }
    </script>
@endpush
