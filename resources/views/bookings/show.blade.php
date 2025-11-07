@extends('layouts.app')

@section('content')
    <section id="bookings" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            {{-- Header --}}
            <div class="text-center mb-12 mt-8">
                <h2 class="text-3xl md:text-4xl font-bold font-playfair text-gray-900">
                    {{ __('booking.booking_detail_title') }}</h2>
                <p class="text-lg text-gray-600 max-w-xl mx-auto mt-2">{{ __('booking.booking_detail_subtitle') }}</p>
            </div>

            {{-- Booking Card --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="p-6 space-y-6">

                    {{-- Booking Overview --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                        {{-- Customer Name --}}
                        <div>
                            <h4 class="text-sm text-gray-500 uppercase mb-1">{{ __('booking.customer') }}</h4>
                            <p class="text-xl font-semibold text-gray-800">
                                <i class="fas fa-user mr-2 text-blue-600"></i>
                                {{ $booking->name }}
                            </p>
                        </div>

                        {{-- Queue --}}
                        @if (isset($queuePosition) && $queuePosition)
                            <div>
                                <h4 class="text-sm text-gray-500 uppercase mb-1">{{ __('booking.queue_number') }}</h4>
                                <p class="text-xl font-semibold text-blue-600">#{{ $queuePosition }}</p>
                                @if (isset($estimatedWaitTime) && $estimatedWaitTime > 0)
                                    <p class="text-sm text-gray-500 mt-1">{{ __('booking.estimated_wait') }}:
                                        {{ $estimatedWaitTime }} {{ __('booking.minutes') }}</p>
                                @endif
                            </div>
                        @endif

                        {{-- Tanggal Booking --}}
                        <div>
                            <h4 class="text-sm text-gray-500 uppercase mb-1">{{ __('booking.booking_date') }}</h4>
                            <p class="text-xl font-semibold text-gray-800">
                                <i class="fas fa-calendar-alt mr-2 text-green-600"></i>
                                {{ $booking->date_time->format('d M Y') }}
                            </p>
                            <p class="text-sm text-gray-500">{{ $booking->date_time->format('H:i') }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm text-gray-500 uppercase mb-1">{{ __('booking.payment_status') }}</h4>
                            <p class="text-xl font-semibold text-gray-800">
                                @if ($booking->transaction)
                                    @if ($booking->transaction->transaction_status === 'settlement')
                                        <i class="fas fa-check-circle mr-2 text-green-600"></i>
                                        {{ __('booking.payment_success') }}
                                    @elseif ($booking->transaction->transaction_status === 'pending')
                                        <i class="fas fa-clock mr-2 text-yellow-500"></i>
                                        {{ __('booking.payment_pending') }}
                                    @else
                                        <i class="fas fa-question-circle mr-2 text-gray-500"></i>
                                        {{ __('booking.payment_method_unknown') }}
                                    @endif
                                @else
                                    <i class="fas fa-exclamation-circle mr-2 text-gray-500"></i>
                                    {{ __('booking.payment_method_unknown') }}
                                @endif
                            </p>
                        </div>

                        {{-- Status --}}
                        <div>
                            <h4 class="text-sm text-gray-500 uppercase mb-1">{{ __('booking.status') }}</h4>
                            <span
                                class="inline-block px-3 py-1 rounded-full text-sm font-medium
                                @switch($booking->status)
                                    @case('pending')
                                        bg-yellow-100 text-yellow-700
                                        @break
                                    @case('confirmed')
                                        bg-blue-100 text-blue-700
                                        @break
                                    @case('in_progress')
                                        bg-purple-100 text-purple-700
                                        @break
                                    @case('completed')
                                        bg-green-100 text-green-700
                                        @break
                                    @case('cancelled')
                                        bg-red-100 text-red-700
                                        @break
                                    @default
                                        bg-gray-100 text-gray-600
                                @endswitch">
                                <i class="fas fa-circle mr-2 text-xs"></i>
                                {{ __('booking.status_' . $booking->status) }}
                            </span>
                        </div>

                        {{-- Payment Status & Total --}}
                        <div>
                            <h4 class="text-sm text-gray-500 uppercase mb-1">{{ __('booking.payment') }}</h4>
                            <div class="space-y-1">
                                <span
                                    class="inline-block px-3 py-1 rounded-full text-sm font-medium
        @switch($booking->payment_method)
            @case('cash')
                bg-blue-100 text-blue-700
                @break
            @case('bank')
                bg-purple-100 text-purple-700
                @break
            @default
                bg-gray-100 text-gray-600
        @endswitch">
                                    @switch($booking->payment_method)
                                        @case('cash')
                                            {{ __('booking.payment_method_cash') }}
                                        @break

                                        @case('bank')
                                            {{ __('booking.payment_method_bank') }}
                                        @break

                                        @default
                                            {{ __('booking.payment_method_' . $booking->payment_method) }}
                                    @endswitch
                                </span>

                                @if (isset($booking->total_price) && $booking->total_price)
                                    <p class="text-lg font-bold text-gray-800">
                                        <i class="fas fa-money-bill-wave mr-1 text-green-600"></i>
                                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                    </p>
                                @endif
                            </div>

                        </div>
                    </div>

                    {{-- Service and Hairstyle Details --}}
                    @if (isset($booking->service) || isset($booking->hairstyle))
                        <div class="border-t pt-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">{{ __('booking.service_details') }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @if (isset($booking->service))
                                    <div>
                                        <h5 class="text-sm text-gray-500 uppercase mb-1">{{ __('booking.service_label') }}
                                        </h5>
                                        <p class="text-lg font-medium text-gray-800">{{ $booking->service->name }}</p>
                                        @if (isset($booking->service->price))
                                            <p class="text-sm text-gray-500">Rp
                                                {{ number_format($booking->service->price, 0, ',', '.') }}</p>
                                        @endif
                                    </div>
                                @endif


                            </div>

                            @if (isset($booking->description) && $booking->description)
                                <div class="mt-4">
                                    <h5 class="text-sm text-gray-500 uppercase mb-1">{{ __('booking.notes') }}</h5>
                                    <p class="text-gray-700">{{ $booking->description }}</p>
                                </div>
                            @endif

                            @if (isset($booking->description) && $booking->description)
                                <div class="mt-4">
                                    <h5 class="text-sm text-gray-500 uppercase mb-1">{{ __('booking.hairstyle') }}</h5>
                                    <p class="text-lg font-medium text-gray-800">{{ $booking->hairstyle->name }}</p>
                                    @if (isset($booking->hairstyle->price))
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="border-t pt-6 flex flex-wrap gap-3 justify-center sm:justify-end">

                        {{-- Back to Bookings List --}}
                        <a href="{{ route('bookings.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            {{ __('booking.back') }}
                        </a>

                        {{-- Edit Booking (only if status allows and no pending transaction) --}}
                        @if (in_array($booking->status, ['pending', 'confirmed']) &&
                                (!isset($booking->transaction) ||
                                    !in_array($booking->transaction->transaction_status ?? '', ['pending', 'settlement'])))
                            <a href="{{ route('bookings.edit', $booking->id) }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                <i class="fas fa-edit mr-2"></i>
                                {{ __('booking.edit_booking') }}
                            </a>
                        @endif

                        {{-- Cancel Booking (only if not completed, cancelled, or confirmed) --}}
                        @if (!in_array($booking->status, ['completed', 'cancelled', 'confirmed']))
                            <button type="button"
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition duration-200"
                                onclick="cancelBooking({{ $booking->id }}, '{{ $booking->name }}')">
                                <i class="fas fa-times mr-2"></i>
                                {{ __('booking.cancel') }}
                            </button>
                        @endif

                        {{-- Payment Button / Transaction Status Button --}}
                        @if (!in_array($booking->status, ['completed', 'cancelled']))
                            @if ($booking->payment_method === 'bank')
                                @if ($booking->transaction && in_array($booking->transaction->transaction_status, ['pending', 'settlement']))
                                    <!-- Button untuk melihat transaksi jika sudah ada transaksi -->
                                    <a href="{{ route('payment.show', $booking->id) }}"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                        <i class="fas fa-eye mr-2"></i>
                                        {{ __('booking.view_transaction') }}
                                    </a>
                                @else
                                    <!-- Tombol untuk Midtrans (Bank) - hanya jika belum ada transaksi -->
                                    <button type="button" id="pay-button-{{ $booking->id }}"
                                        onclick="initiatePayment({{ $booking->id }})"
                                        class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                        <i class="fas fa-credit-card mr-2"></i>
                                        {{ __('booking.pay_now') }}
                                    </button>
                                @endif
                            @elseif (
                                $booking->payment_method === 'cash' &&
                                    !in_array(optional($booking->transaction)->transaction_status, ['settlement', 'pending']))
                                <!-- Tombol untuk Cash -->
                                <form action="{{ route('payment.cash') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                    <input type="hidden" name="payment_method" value="cash">
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                        <i class="fas fa-money-bill-wave mr-2"></i>
                                        {{ __('booking.pay_cash') }}
                                    </button>
                                </form>
                                {{-- @elseif ($booking->payment_method === 'cash' && $booking->transaction)
                                <!-- Button untuk melihat transaksi cash jika sudah ada -->
                                <a href="{{ route('payment.show', $booking->id) }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                    <i class="fas fa-eye mr-2"></i>
                                    {{ __('booking.view_transaction') }}
                                </a> --}}
                            @endif
                        @endif

                        <script src="https://app.sandbox.midtrans.com/snap/snap.js"
                            data-client-key="{{ config('services.midtrans.client_key') }}"></script>

                        <script>
                            function initiatePayment(bookingId) {
                                fetch(`/payment/${bookingId}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        snap.pay(data.snapToken, {
                                            onSuccess: function(result) {
                                                console.log("{{ __('booking.success_payment') }}", result);
                                                location.reload();
                                            },
                                            onPending: function(result) {
                                                console.log("{{ __('booking.pending_payment') }}", result);
                                                location.reload();
                                            },
                                            onError: function(result) {
                                                console.error("{{ __('booking.error_payment') }}", result);
                                            },
                                            onClose: function() {
                                                alert('{{ __('booking.payment_cancelled') }}');
                                            }
                                        });
                                    });
                            }
                        </script>


                        {{-- Print Receipt (if completed) --}}
                        @if ($booking->status === 'completed')
                            {{-- Feedback Button --}}
                            @php
                                $existingFeedback = \App\Models\Feedback::where('user_id', auth()->id())
                                    ->where('booking_id', $booking->id)
                                    ->first();
                            @endphp

                            @if ($existingFeedback)
                                <a href="{{ route('feedback.show', $existingFeedback->id) }}"
                                    class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-lg shadow transition duration-200">
                                    <i class="fas fa-star mr-2"></i>
                                    {{ __('booking.view_feedback') }}
                                </a>
                            @else
                                <button type="button" onclick="openFeedbackModal()"
                                    class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-lg shadow transition duration-200">
                                    <i class="fas fa-comment mr-2"></i>
                                    {{ __('booking.give_feedback') }}
                                </button>
                            @endif

                            <a href="{{ route('dashboard') }}#reservasi"
                                class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                {{ __('booking.new_booking') }}
                            </a>
                        @endif

                        {{-- Create New Booking --}}


                    </div>

                </div>
            </div>
        </div>
    </section>



@endsection




@push('scripts')

    {{-- Success/Error Messages --}}
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '{{ __('booking.success') }}!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#2563eb',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: '{{ __('booking.oops') }}!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#dc2626'
                });
            });
        </script>
    @endif

    {{-- Feedback Modal Script --}}
    @if ($booking->status === 'completed')
        <script>
            function openFeedbackModal() {
                const translations = {
                    give_feedback: @json(__('booking.give_feedback')),
                    rating: @json(__('booking.rating')),
                    comment: @json(__('booking.comment')),
                    public_feedback: @json(__('booking.public_feedback')),
                    submit_feedback: @json(__('booking.submit_feedback')),
                    cancel: @json(__('booking.cancel')),
                    rating_required: @json(__('booking.rating_required')),
                    comment_required: @json(__('booking.comment_required')),

                };

                Swal.fire({
                    title: translations.give_feedback,
                    html: `
        <div class="text-left">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">${translations.rating}</label>
                <div class="flex justify-center space-x-1" id="rating-stars">
                    <i class="fas fa-star text-gray-300 cursor-pointer text-2xl" data-rating="1"></i>
                    <i class="fas fa-star text-gray-300 cursor-pointer text-2xl" data-rating="2"></i>
                    <i class="fas fa-star text-gray-300 cursor-pointer text-2xl" data-rating="3"></i>
                    <i class="fas fa-star text-gray-300 cursor-pointer text-2xl" data-rating="4"></i>
                    <i class="fas fa-star text-gray-300 cursor-pointer text-2xl" data-rating="5"></i>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">${translations.comment}</label>
                <textarea id="feedback-comment" class="w-full p-3 border border-gray-300 rounded-lg resize-none" 
                         rows="4" placeholder="${translations.comment_required}"></textarea>
            </div>
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" id="feedback-public" class="mr-2">
                    <span class="text-sm text-gray-700">${translations.public_feedback}</span>
                </label>
            </div>
        </div>
    `,
                    showCancelButton: true,
                    confirmButtonText: translations.submit_feedback,
                    cancelButtonText: translations.cancel,
                    confirmButtonColor: '#3b82f6',
                    cancelButtonColor: '#6b7280',
                    width: '500px',
                    didOpen: () => {
                        let selectedRating = 0;
                        const stars = document.querySelectorAll('#rating-stars i');

                        stars.forEach((star, index) => {
                            star.addEventListener('click', () => {
                                selectedRating = index + 1;
                                updateStars(selectedRating);
                                window.selectedRating = selectedRating;
                            });

                            star.addEventListener('mouseenter', () => {
                                updateStars(index + 1);
                            });
                        });

                        document.getElementById('rating-stars').addEventListener('mouseleave', () => {
                            updateStars(selectedRating);
                        });

                        function updateStars(rating) {
                            stars.forEach((star, index) => {
                                if (index < rating) {
                                    star.classList.remove('text-gray-300');
                                    star.classList.add('text-yellow-400');
                                } else {
                                    star.classList.remove('text-yellow-400');
                                    star.classList.add('text-gray-300');
                                }
                            });
                        }
                    },
                    preConfirm: () => {
                        const rating = window.selectedRating || 0;
                        const comment = document.getElementById('feedback-comment').value;
                        const isPublic = document.getElementById('feedback-public').checked;

                        if (rating === 0) {
                            Swal.showValidationMessage(translations.rating_required);
                            return false;
                        }

                        if (!comment.trim()) {
                            Swal.showValidationMessage(translations.comment_required);
                            return false;
                        }

                        return {
                            rating,
                            comment,
                            isPublic
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitFeedback(result.value.rating, result.value.comment, result.value.isPublic);
                    }
                });

            }

            function submitFeedback(rating, comment, isPublic) {
                fetch('{{ route('feedback.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            booking_id: {{ $booking->id }},
                            rating: rating,
                            comment: comment,
                            is_public: isPublic
                        })
                    })
                    .then(response => {
                        // Check if response is ok (status 200-299)
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        // Check if response is JSON
                        const contentType = response.headers.get("content-type");
                        if (!contentType || !contentType.includes("application/json")) {
                            throw new Error("Response is not JSON");
                        }

                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '{{ __('booking.thank_you') }}',
                                text: '{{ __('booking.feedback_sent_success') }}',
                                confirmButtonColor: '#3b82f6'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: '{{ __('booking.oops') }}!',
                                text: data.message || '{{ __('booking.feedback_send_error') }}',
                                confirmButtonColor: '#dc2626'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error details:', error);
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __('booking.oops') }}!',
                            text: '{{ __('booking.feedback_network_error') }}',
                            confirmButtonColor: '#dc2626'
                        });
                    });
            }
        </script>
    @endif



@endpush

{{-- Cancel Booking Scripts --}}
@push('scripts')
    {{-- SweetAlert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Cancel Booking Script --}}
    <script>
        // Translation variables for cancel booking
        const cancelTranslations = {
            confirm_cancel: @json(__('booking.confirm_cancel_booking')),
            cancel_warning: @json(__('booking.cancel_warning_message')),
            yes_cancel: @json(__('booking.yes_cancel_booking')),
            no_keep: @json(__('booking.no_keep_booking')),
            cancelling: @json(__('booking.cancelling_booking')),
            cancelled_successfully: @json(__('booking.booking_cancelled_successfully')),
            cancel_failed: @json(__('booking.cancel_failed')),
            error_occurred: @json(__('booking.error_occurred')),
            ok: @json(__('booking.ok'))
        };

        function cancelBooking(bookingId, bookingName) {
            Swal.fire({
                title: cancelTranslations.confirm_cancel,
                html: `
                    <div class="text-left">
                        <p class="mb-3">{{ __('booking.are_you_sure_cancel') }} <strong>${bookingName}</strong>?</p>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                                <span class="font-bold text-red-800">${cancelTranslations.cancel_warning}</span>
                            </div>
                            <ul class="text-sm text-red-700 ml-6 list-disc">
                                <li>{{ __('booking.booking_will_be_cancelled') }}</li>
                                <li>{{ __('booking.transaction_will_be_cancelled') }}</li>
                                <li>{{ __('booking.action_cannot_be_undone') }}</li>
                            </ul>
                        </div>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: cancelTranslations.yes_cancel,
                cancelButtonText: cancelTranslations.no_keep,
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: cancelTranslations.cancelling,
                        html: `
                            <div class="text-center">
                                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-red-600 mx-auto mb-4"></div>
                                <p>{{ __('booking.please_wait_cancelling') }}...</p>
                            </div>
                        `,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false
                    });

                    // Create form and submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/bookings/${bookingId}`;
                    form.style.display = 'none';

                    // Add CSRF token
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);

                    // Add method spoofing for DELETE
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endpush
