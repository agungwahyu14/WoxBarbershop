@extends('layouts.app')

@section('title', 'Booking Details - #' . $booking->id)
@section('meta_description', 'View detailed information about your booking at WOX Barbershop')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-green-600 inline-flex items-center">
                                    <i class="fas fa-home mr-2"></i>
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                    <span class="text-gray-500">Booking Details</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="mt-4 text-3xl font-bold text-gray-900">
                        <i class="fas fa-calendar-check mr-3 text-green-600"></i>
                        Booking #{{ $booking->id }}
                    </h1>
                    <p class="mt-2 text-lg text-gray-600">
                        View and manage your booking details
                    </p>
                </div>
                
                <!-- Status Badge -->
                <div class="text-right">
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                            'confirmed' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'in_progress' => 'bg-orange-100 text-orange-800 border-orange-200',
                            'completed' => 'bg-green-100 text-green-800 border-green-200',
                            'cancelled' => 'bg-red-100 text-red-800 border-red-200'
                        ];
                        $statusIcons = [
                            'pending' => 'fas fa-clock',
                            'confirmed' => 'fas fa-check',
                            'in_progress' => 'fas fa-cut',
                            'completed' => 'fas fa-check-circle',
                            'cancelled' => 'fas fa-times-circle'
                        ];
                    @endphp
                    
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium border {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                        <i class="{{ $statusIcons[$booking->status] ?? 'fas fa-question-circle' }} mr-2"></i>
                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Booking Information Card -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-info-circle mr-3 text-blue-600"></i>
                            Booking Information
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Date & Time -->
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-green-600"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Date & Time</h3>
                                    <p class="text-gray-600">{{ \Carbon\Carbon::parse($booking->date_time)->format('l, M d, Y') }}</p>
                                    <p class="text-lg font-bold text-green-600">{{ \Carbon\Carbon::parse($booking->date_time)->format('H:i A') }}</p>
                                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($booking->date_time)->diffForHumans() }}</p>
                                </div>
                            </div>

                            <!-- Queue Information -->
                            @if($queuePosition)
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-users text-blue-600"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Queue Position</h3>
                                    <p class="text-2xl font-bold text-blue-600">#{{ $queuePosition }}</p>
                                    @if($estimatedWaitTime && $estimatedWaitTime > 0)
                                        <p class="text-sm text-gray-500">Est. wait: {{ $estimatedWaitTime }} minutes</p>
                                    @endif
                                </div>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>

                <!-- Service Details Card -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-cut mr-3 text-purple-600"></i>
                            Service Details
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Service Info -->
                            @if($booking->service)
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $booking->service->name }}</h3>
                                    <p class="text-gray-600">{{ $booking->service->description ?? 'Professional barbershop service' }}</p>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Price:</span>
                                    <span class="text-xl font-bold text-green-600">Rp {{ number_format($booking->service->price, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Duration:</span>
                                    <span class="text-gray-900 font-medium">{{ $booking->service->duration ?? 30 }} minutes</span>
                                </div>
                            </div>
                            @endif

                            <!-- Hairstyle Info -->
                            @if($booking->hairstyle)
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $booking->hairstyle->name }}</h3>
                                    <p class="text-gray-600">{{ $booking->hairstyle->description ?? 'Classic hairstyle' }}</p>
                                </div>
                                @if($booking->hairstyle->bentuk_kepala)
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Face Shape:</span>
                                    <span class="text-gray-900 font-medium">{{ $booking->hairstyle->bentuk_kepala }}</span>
                                </div>
                                @endif
                                @if($booking->hairstyle->tipe_rambut)
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Hair Type:</span>
                                    <span class="text-gray-900 font-medium">{{ $booking->hairstyle->tipe_rambut }}</span>
                                </div>
                                @endif
                            </div>
                            @endif

                        </div>
                    </div>
                </div>

                <!-- Transaction History -->
                @if($booking->transactions && $booking->transactions->count() > 0)
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-credit-card mr-3 text-green-600"></i>
                            Payment Information
                        </h2>
                    </div>
                    <div class="p-6">
                        @foreach($booking->transactions as $transaction)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg {{ !$loop->last ? 'mb-4' : '' }}">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-receipt text-green-600"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Transaction #{{ $transaction->id }}</p>
                                    <p class="text-sm text-gray-600">{{ $transaction->created_at->format('M d, Y H:i') }}</p>
                                    <p class="text-sm text-gray-500">Method: {{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-900">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
                                @php
                                    $paymentStatusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'paid' => 'bg-green-100 text-green-800',
                                        'failed' => 'bg-red-100 text-red-800',
                                        'cancelled' => 'bg-gray-100 text-gray-800',
                                    ];
                                @endphp
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $paymentStatusColors[$transaction->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($transaction->payment_status) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Service Recommendations -->
                @if($recommendations && $recommendations->count() > 0)
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-lightbulb mr-3 text-yellow-600"></i>
                            Recommended Services
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($recommendations as $service)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <h4 class="font-semibold text-gray-900">{{ $service->name }}</h4>
                                <p class="text-sm text-gray-600 mt-1">{{ Str::limit($service->description, 60) }}</p>
                                <div class="mt-3 flex items-center justify-between">
                                    <span class="text-lg font-bold text-green-600">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                                    <span class="text-sm text-gray-500">{{ $service->duration }}min</span>
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
                @if($booking->user)
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center">
                            <i class="fas fa-user mr-3 text-blue-600"></i>
                            Customer Info
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white text-xl font-bold">
                                {{ strtoupper(substr($booking->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $booking->user->name }}</h3>
                                <p class="text-gray-600">{{ $booking->user->email }}</p>
                                @if($booking->user->no_telepon)
                                <p class="text-gray-600">{{ $booking->user->no_telepon }}</p>
                                @endif
                            </div>
                        </div>

                        @if($customerStats)
                        <div class="border-t border-gray-200 pt-4 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Bookings:</span>
                                <span class="font-semibold">{{ $customerStats['total_bookings'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Completed:</span>
                                <span class="font-semibold text-green-600">{{ $customerStats['completed_bookings'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Spent:</span>
                                <span class="font-semibold text-green-600">Rp {{ number_format($customerStats['total_spent'], 0, ',', '.') }}</span>
                            </div>
                            @if($customerStats['last_booking'])
                            <div class="flex justify-between">
                                <span class="text-gray-600">Last Visit:</span>
                                <span class="font-semibold">{{ \Carbon\Carbon::parse($customerStats['last_booking'])->format('M d, Y') }}</span>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center">
                            <i class="fas fa-cogs mr-3 text-green-600"></i>
                            Actions
                        </h2>
                    </div>
                    <div class="p-6 space-y-3">
                        
                        @if($canModify)
                        <a href="{{ route('bookings.edit', $booking) }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Booking
                        </a>
                        @endif

                        @if($canCancel)
                        <button onclick="cancelBooking({{ $booking->id }})"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 rounded-lg shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Cancel Booking
                        </button>
                        @endif

                        <a href="{{ route('dashboard') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Dashboard
                        </a>

                        <button onclick="printBooking()"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            <i class="fas fa-print mr-2"></i>
                            Print Details
                        </button>

                    </div>
                </div>

                <!-- Available Reschedule Slots -->
                @if($availableSlots && count($availableSlots) > 0 && $canModify)
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center">
                            <i class="fas fa-clock mr-3 text-orange-600"></i>
                            Quick Reschedule
                        </h2>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-4">Available slots in the next 7 days:</p>
                        <div class="space-y-2 max-h-48 overflow-y-auto">
                            @foreach(array_slice($availableSlots, 0, 10) as $slot)
                            <button onclick="rescheduleBooking('{{ $slot }}')"
                                    class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg border border-gray-200 transition-colors">
                                {{ \Carbon\Carbon::parse($slot)->format('M d, Y - H:i A') }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

<!-- Cancel Confirmation Modal -->
<div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Cancel Booking</h3>
                </div>
                <p class="text-gray-600 mb-6">Are you sure you want to cancel this booking? This action cannot be undone.</p>
                <div class="flex space-x-3">
                    <button onclick="confirmCancel()" 
                            class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                        Yes, Cancel
                    </button>
                    <button onclick="closeCancelModal()" 
                            class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                        No, Keep
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let bookingToCancel = null;

function cancelBooking(bookingId) {
    bookingToCancel = bookingId;
    document.getElementById('cancelModal').classList.remove('hidden');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
    bookingToCancel = null;
}

function confirmCancel() {
    if (bookingToCancel) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/bookings/${bookingToCancel}`;
        
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

function rescheduleBooking(newDateTime) {
    // Redirect to edit page with pre-selected datetime
    window.location.href = `{{ route('bookings.edit', $booking) }}?datetime=${encodeURIComponent(newDateTime)}`;
}

function printBooking() {
    window.print();
}

// Auto-refresh status every 30 seconds for active bookings
@if(in_array($booking->status, ['pending', 'confirmed', 'in_progress']))
setInterval(function() {
    // You can implement AJAX status checking here
    // location.reload();
}, 30000);
@endif

// Success/Error notifications
@if(session('success'))
    showNotification('success', 'Success!', '{{ session('success') }}');
@endif

@if(session('error'))
    showNotification('error', 'Error!', '{{ session('error') }}');
@endif

function showNotification(type, title, message) {
    // Implement your notification system here
    alert(title + ': ' + message);
}
</script>
@endpush

@push('styles')
<style>
@media print {
    body * {
        visibility: hidden;
    }
    .print-section, .print-section * {
        visibility: visible;
    }
    .print-section {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .no-print {
        display: none !important;
    }
}

.card-hover {
    transition: all 0.3s ease;
}

.card-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}
</style>
@endpush