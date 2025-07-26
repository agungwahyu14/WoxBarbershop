@extends('admin.layouts.app')

@section('title', 'Booking Details - #' . $booking->id)
@section('meta_description', 'Detailed booking information for administrative management')

@section('content')
    <div class="is-hero-bar">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold mb-2">
                    <i class="fas fa-calendar-check mr-3"></i>Booking Details
                </h1>
                <p class="text-lg">
                    Manage booking #{{ $booking->id }} - {{ $booking->user->name ?? 'Unknown Customer' }}
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
                            Booking Overview
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
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Appointment</h3>
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
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Queue Position
                                            </h3>
                                            <p class="text-2xl font-bold text-blue-600">#{{ $queuePosition }}</p>
                                            @if ($estimatedWaitTime && $estimatedWaitTime > 0)
                                                <p class="text-sm text-gray-500">Est. wait: {{ $estimatedWaitTime }} min</p>
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
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Total Amount</h3>
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
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Duration</h3>
                                        <p class="text-lg font-bold text-purple-600">
                                            {{ $booking->service->duration ?? 30 }} minutes</p>
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
                                    Service Details
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
                                            <span class="text-gray-600 dark:text-gray-400">Price:</span>
                                            <p class="font-bold text-green-600">Rp
                                                {{ number_format($booking->service->price, 0, ',', '.') }}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-600 dark:text-gray-400">Duration:</span>
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
                                    Hairstyle Details
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
                                        @if ($booking->hairstyle->bentuk_kepala)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Face Shape:</span>
                                                <span
                                                    class="font-medium text-gray-900 dark:text-white">{{ $booking->hairstyle->bentuk_kepala }}</span>
                                            </div>
                                        @endif
                                        @if ($booking->hairstyle->tipe_rambut)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Hair Type:</span>
                                                <span
                                                    class="font-medium text-gray-900 dark:text-white">{{ $booking->hairstyle->tipe_rambut }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

                <!-- Transaction History -->
                @if ($booking->transactions && $booking->transactions->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                                <i class="fas fa-credit-card mr-3 text-green-600"></i>
                                Transaction History
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach ($booking->transactions as $transaction)
                                    <div
                                        class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        <div class="flex items-center space-x-4">
                                            <div
                                                class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-receipt text-green-600"></i>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900 dark:text-white">Transaction
                                                    #{{ $transaction->id }}</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $transaction->created_at->format('M d, Y H:i') }}</p>
                                                <p class="text-sm text-gray-500">Method:
                                                    {{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-bold text-gray-900 dark:text-white">Rp
                                                {{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
                                            @php
                                                $paymentStatusColors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'paid' => 'bg-green-100 text-green-800',
                                                    'failed' => 'bg-red-100 text-red-800',
                                                    'cancelled' => 'bg-gray-100 text-gray-800',
                                                ];
                                            @endphp
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $paymentStatusColors[$transaction->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($transaction->payment_status) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            <!-- Sidebar -->
            <div class="space-y-6">

                <!-- Customer Information -->
                @if ($booking->user)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                                <i class="fas fa-user mr-3 text-blue-600"></i>
                                Customer Information
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

                            @if ($customerStats)
                                <div class="border-t border-gray-200 dark:border-gray-600 pt-4 space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Total Bookings:</span>
                                        <span
                                            class="font-semibold text-gray-900 dark:text-white">{{ $customerStats['total_bookings'] }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Completed:</span>
                                        <span
                                            class="font-semibold text-green-600">{{ $customerStats['completed_bookings'] }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Total Spent:</span>
                                        <span class="font-semibold text-green-600">Rp
                                            {{ number_format($customerStats['total_spent'], 0, ',', '.') }}</span>
                                    </div>
                                    @if ($customerStats['last_booking'])
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Last Visit:</span>
                                            <span
                                                class="font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($customerStats['last_booking'])->format('M d, Y') }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-cogs mr-3 text-green-600"></i>
                            Actions
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">

                        <!-- Status Update Buttons -->
                        @if ($booking->status === 'pending')
                            <button onclick="updateStatus('confirmed')"
                                class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-check mr-2"></i>
                                Confirm Booking
                            </button>
                        @elseif($booking->status === 'confirmed')
                            <button onclick="updateStatus('in_progress')"
                                class="w-full bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors">
                                <i class="fas fa-play mr-2"></i>
                                Start Service
                            </button>
                        @elseif($booking->status === 'in_progress')
                            <button onclick="updateStatus('completed')"
                                class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-check-circle mr-2"></i>
                                Complete Service
                            </button>
                        @endif

                        @if ($canCancel)
                            <button onclick="cancelBooking({{ $booking->id }})"
                                class="w-full border border-red-300 text-red-700 px-4 py-2 rounded-lg hover:bg-red-50 transition-colors">
                                <i class="fas fa-times mr-2"></i>
                                Cancel Booking
                            </button>
                        @endif

                        <a href="{{ route('bookings.index') }}"
                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Bookings
                        </a>

                    </div>
                </div>

                <!-- Service Recommendations -->

            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function updateStatus(newStatus) {
            if (confirm(`Are you sure you want to update the booking status to ${newStatus}?`)) {
                fetch(`{{ route('bookings.updateStatus', $booking) }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            status: newStatus
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('success', 'Success', data.message);
                            location.reload();
                        } else {
                            showNotification('error', 'Error', data.message);
                        }
                    })
                    .catch(error => {
                        showNotification('error', 'Error', 'Failed to update status');
                    });
            }
        }

        function cancelBooking(bookingId) {
            if (confirm('Are you sure you want to cancel this booking?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/bookings/${bookingId}`;

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';

                const tokenField = document.createElement('input');
                tokenField.type = 'hidden';
                tokenField.name = '_token';
                tokenField.value = '{{ csrf_token() }}';

                form.appendChild(methodField);
                form.appendChild(tokenField);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function showNotification(type, title, message) {
            // Implement your notification system here
            alert(title + ': ' + message);
        }

        // Auto-refresh for active bookings
        @if (in_array($booking->status, ['pending', 'confirmed', 'in_progress']))
            setInterval(function() {
                // You can implement AJAX status checking here
            }, 30000);
        @endif
    </script>
@endpush
