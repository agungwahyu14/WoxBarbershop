@extends('layouts.app')

@section('content')
    <section id="edit-booking" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            {{-- Header --}}
            <div class="text-center mb-12 mt-8">
                <h2 class="text-3xl md:text-4xl font-bold font-playfair text-gray-900">{{ __('booking.edit_booking') }}</h2>
                <p class="text-lg text-gray-600 max-w-xl mx-auto mt-2">{{ __('booking.edit_booking_subtitle') }}</p>
            </div>

            {{-- Edit Form Card --}}
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    {{-- Card Header --}}
                    <div class="bg-black px-6 py-4">
                        <h3 class="text-white font-semibold text-lg">
                            <i class="fas fa-edit mr-2"></i>
                            {{ __('booking.edit_booking') }} #{{ $booking->id }}
                        </h3>
                        <p class="text-blue-100 text-sm">{{ __('booking.update_booking_info') }}</p>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-6">
                        {{-- Current Booking Info --}}
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <h4 class="text-sm font-semibold text-gray-700 uppercase mb-2">
                                {{ __('booking.current_information') }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">{{ __('booking.status') }}:</span>
                                    <span
                                        class="ml-2 px-2 py-1 rounded-full text-xs font-medium
                                        {{ $booking->status === 'pending'
                                            ? 'bg-yellow-100 text-yellow-700'
                                            : ($booking->status === 'confirmed'
                                                ? 'bg-green-100 text-green-700'
                                                : 'bg-gray-100 text-gray-600') }}">
                                        {{ __('booking.status_' . $booking->status) }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-500">{{ __('booking.queue_number') }}:</span>
                                    <span class="ml-2 font-semibold text-blue-600">#{{ $booking->queue_number }}</span>
                                </div>
                                @if ($booking->payment_status)
                                    <div>
                                        <span class="text-gray-500">{{ __('booking.payment_status') }}:</span>
                                        <span
                                            class="ml-2 px-2 py-1 rounded-full text-xs font-medium
                                            {{ $booking->payment_status === 'paid'
                                                ? 'bg-green-100 text-green-700'
                                                : ($booking->payment_status === 'pending'
                                                    ? 'bg-yellow-100 text-yellow-700'
                                                    : 'bg-red-100 text-red-700') }}">
                                            {{ __('booking.payment_status_' . $booking->payment_status) }}
                                        </span>
                                    </div>
                                @endif
                                @if ($booking->total_price)
                                    <div>
                                        <span class="text-gray-500">{{ __('booking.total') }}:</span>
                                        <span class="ml-2 font-semibold text-green-600"
                                            id="current-price">{{ __('booking.currency_symbol') }}
                                            {{ number_format($booking->total_price, 0, __('booking.currency_format_thousands_separator'), __('booking.currency_format_decimal_separator')) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Edit Form --}}
                        <form action="{{ route('bookings.update', $booking->id) }}" method="POST" class="space-y-6"
                            id="editBookingForm">
                            @csrf
                            @method('PUT')

                            {{-- Name --}}
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user mr-1 text-[#d4af37]   "></i>
                                    {{ __('booking.customer_name') }}
                                </label>
                                <input type="text" name="name" id="name"
                                    value="{{ old('name', $booking->name) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                    placeholder="{{ __('booking.enter_customer_name') }}" required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Date & Time --}}
                            <div>
                                <label for="date_time" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-calendar-alt mr-1 text-[#d4af37]   "></i>
                                    {{ __('booking.date_time') }}
                                </label>
                                <input type="datetime-local" name="date_time" id="date_time"
                                    value="{{ old('date_time', \Carbon\Carbon::parse($booking->date_time)->format('Y-m-d\TH:i')) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date_time') border-red-500 @enderror"
                                    required>
                                @error('date_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <div id="datetime-error" class="mt-1 text-sm text-red-600 hidden"></div>
                            </div>

                            {{-- Service --}}
                            @if (isset($services) && $services->count() > 0)
                                <div>
                                    <label for="service_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-cut mr-1 text-[#d4af37]"></i>
                                        {{ __('booking.service') }} <span class="text-red-500"
                                            title="{{ __('booking.required_field') }}">*</span>
                                    </label>
                                    <select name="service_id" id="service_id" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('service_id') border-red-500 @enderror">
                                        <option value="">{{ __('booking.select_service') }}</option>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}" data-price="{{ $service->price }}"
                                                {{ old('service_id', $booking->service_id) == $service->id ? 'selected' : '' }}>
                                                {{ $service->name }} - {{ __('booking.currency_symbol') }}
                                                {{ number_format($service->price, 0, __('booking.currency_format_thousands_separator'), __('booking.currency_format_decimal_separator')) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('service_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @else
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                    <p class="text-red-600 text-sm">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        {{ __('booking.no_services_available') }}
                                    </p>
                                </div>
                            @endif


                            <div>
                                <label for="hairstyle_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-money-bill mr-1 text-[#d4af37]"></i>
                                    {{ __('welcome.payment') }} <span class="text-red-500"
                                        title="{{ __('booking.required_field') }}">*</span>
                                </label>

                                <select id="payment_method" name="payment_method" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('payment_method') border-red-500 @enderror">
                                    <option value="">{{ __('welcome.choose_payment_method') }}</option>
                                    <option value="cash"
                                        {{ old('payment_method', $booking->payment_method) == 'cash' ? 'selected' : '' }}>
                                        {{ __('welcome.cash') }}
                                    </option>
                                    <option value="bank"
                                        {{ old('payment_method', $booking->payment_method) == 'bank' ? 'selected' : '' }}>
                                        {{ __('welcome.bank') }}
                                    </option>
                                </select>
                                @error('payment_method')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Hairstyle --}}
                            @if (isset($hairstyles) && $hairstyles->count() > 0)
                                <div>
                                    <label for="hairstyle_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-magic mr-1 text-[#d4af37]"></i>
                                        {{ __('booking.hairstyle') }} <span class="text-red-500"
                                            title="{{ __('booking.required_field') }}">*</span>
                                    </label>
                                    <select name="hairstyle_id" id="hairstyle_id" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('hairstyle_id') border-red-500 @enderror">
                                        <option value="">{{ __('booking.select_hairstyle') }}</option>
                                        @foreach ($hairstyles as $style)
                                            <option value="{{ $style->id }}"
                                                {{ old('hairstyle_id', $booking->hairstyle_id) == $style->id ? 'selected' : '' }}>
                                                {{ $style->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('hairstyle_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @else
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                    <p class="text-red-600 text-sm">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        {{ __('booking.no_hairstyles_available') }}
                                    </p>
                                </div>
                            @endif

                            {{-- Description --}}
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-comment-alt mr-1 text-[#d4af37]    "></i>
                                    {{ __('booking.description_notes') }}
                                </label>
                                <textarea name="description" id="description" rows="4"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                    placeholder="{{ __('booking.description_placeholder') }}">{{ old('description', $booking->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t">
                                <a href="{{ route('bookings.show', $booking->id) }}"
                                    class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    {{ __('booking.back') }}
                                </a>

                                <button type="submit"
                                    class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-black hover:bg-[#d4af37] text-white font-medium rounded-lg transition-colors duration-200">
                                    <i class="fas fa-save mr-2"></i>
                                    {{ __('booking.save_changes') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Additional Info Card --}}
                <div class="mt-6 bg-[#d4af37]-50 border border-[#d4af37] rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-[#d4af37]  mt-0.5 mr-3"></i>
                        <div class="text-sm text-[#d4af37]">
                            <h4 class="font-semibold mb-1">{{ __('booking.important_info') }}:</h4>
                            <ul class="list-disc list-inside space-y-1">
                                <li>{{ __('booking.info_time_change') }}</li>
                                <li>{{ __('booking.info_service_change') }}</li>
                                <li>{{ __('booking.info_edit_restriction') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('booking-form');
            const dateTimeInput = document.getElementById('date_time');

            // Real-time validation for date and time
            if (dateTimeInput) {
                dateTimeInput.addEventListener('input', function() {
                    validateDateTime();
                });

                dateTimeInput.addEventListener('change', function() {
                    validateDateTime();
                });
            }

            function validateDateTime() {
                const dateTimeInput = document.getElementById('date_time');
                const errorDiv = document.getElementById('datetime-error');

                if (!dateTimeInput.value) return;

                const selectedDateTime = new Date(dateTimeInput.value);
                const hours = selectedDateTime.getHours();
                const minutes = selectedDateTime.getMinutes();

                let errorMessage = '';

                // Check business hours (11:00 - 22:00)
                if (hours < 11 || hours >= 22) {
                    errorMessage = '{{ __('booking.business_hours_error') }}';
                } else if (hours === 21 && minutes > 0) {
                    // If it's 21:xx, only allow 21:00 as last booking
                    errorMessage = '{{ __('booking.business_hours_error') }}';
                }

                if (errorMessage && errorDiv) {
                    errorDiv.textContent = errorMessage;
                    errorDiv.classList.remove('hidden');
                    dateTimeInput.classList.add('border-red-500');
                } else if (errorDiv) {
                    errorDiv.classList.add('hidden');
                    dateTimeInput.classList.remove('border-red-500');
                }
            }

            // AJAX Form Submission
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const dateTimeInput = document.getElementById('date_time');
                    const serviceInput = document.getElementById('service_id');
                    const hairstyleInput = document.getElementById('hairstyle_id');
                    const nameInput = document.getElementById('name');

                    let hasError = false;
                    let errorMessage = '';

                    // Translation strings
                    const nameRequired = '{{ __('booking.name_required') }}';
                    const serviceRequired = '{{ __('booking.service_required') }}';
                    const hairstyleRequired = '{{ __('booking.hairstyle_required') }}';
                    const datetimeRequired = '{{ __('booking.datetime_required') }}';
                    const businessHoursError = '{{ __('booking.business_hours_error') }}';

                    // Validate required fields
                    if (!nameInput.value.trim()) {
                        hasError = true;
                        errorMessage += '• ' + nameRequired + '\n';
                    }

                    if (!serviceInput.value) {
                        hasError = true;
                        errorMessage += '• ' + serviceRequired + '\n';
                    }

                    if (!hairstyleInput.value) {
                        hasError = true;
                        errorMessage += '• ' + hairstyleRequired + '\n';
                    }

                    if (!dateTimeInput.value) {
                        hasError = true;
                        errorMessage += '• ' + datetimeRequired + '\n';
                    } else {
                        // Validate business hours
                        const selectedDateTime = new Date(dateTimeInput.value);
                        const hours = selectedDateTime.getHours();
                        const minutes = selectedDateTime.getMinutes();

                        if (hours < 11 || hours >= 22 || (hours === 21 && minutes > 0)) {
                            hasError = true;
                            errorMessage += '• ' + businessHoursError + '\n';
                        }
                    }

                    if (hasError) {
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __('booking.validation_error') }}',
                            text: errorMessage,
                            confirmButtonColor: '#d4af37'
                        });
                        return;
                    }

                    // Show loading state
                    const submitButton = form.querySelector('button[type="submit"]');
                    const originalText = submitButton.innerHTML;
                    submitButton.disabled = true;
                    submitButton.innerHTML =
                        '<i class="fas fa-spinner fa-spin mr-2"></i>{{ __('booking.saving') }}';

                    // Prepare form data
                    const formData = new FormData(form);

                    // Submit via AJAX
                    fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalText;

                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '{{ __('booking.success') }}',
                                    text: data.message ||
                                        '{{ __('booking.booking_updated_successfully') }}',
                                    confirmButtonColor: '#d4af37'
                                }).then(() => {
                                    // Redirect to booking show page
                                    window.location.href =
                                        '{{ route('bookings.show', $booking->id) }}';
                                });
                            } else {
                                let errorMsg = '';
                                if (data.errors) {
                                    Object.values(data.errors).forEach(errors => {
                                        errors.forEach(error => {
                                            errorMsg += '• ' + error + '\n';
                                        });
                                    });
                                } else {
                                    errorMsg = data.message || '{{ __('booking.update_failed') }}';
                                }

                                if (data.alternatives && data.alternatives.length > 0) {
                                    let alternativesText =
                                        '\n\n{{ __('booking.alternative_times') }}:\n';
                                    data.alternatives.forEach(alt => {
                                        alternativesText += '• ' + alt + '\n';
                                    });
                                    errorMsg += alternativesText;
                                }

                                Swal.fire({
                                    icon: 'error',
                                    title: '{{ __('booking.error') }}',
                                    text: errorMsg,
                                    confirmButtonColor: '#d4af37'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalText;

                            Swal.fire({
                                icon: 'error',
                                title: '{{ __('booking.error') }}',
                                text: '{{ __('booking.network_error') }}',
                                confirmButtonColor: '#d4af37'
                            });
                        });
                });
            }

            // Handle validation errors from server
            @if ($errors->any())
                let errorMessage = '';
                @foreach ($errors->all() as $error)
                    errorMessage += '• {{ $error }}\n';
                @endforeach

                Swal.fire({
                    icon: 'error',
                    title: '{{ __('booking.validation_error') }}',
                    text: errorMessage,
                    confirmButtonColor: '#d4af37'
                });
            @endif

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '{{ __('booking.success') }}',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#d4af37'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: '{{ __('booking.error') }}',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#d4af37'
                });
            @endif
        });
    </script>

@endsection
