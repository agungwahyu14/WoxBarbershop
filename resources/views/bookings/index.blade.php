@extends('layouts.app')

@section('content')
    <section id="bookings" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            {{-- Header --}}
            <div class="text-center mb-10 mt-8">
                <h2 class="text-3xl md:text-4xl font-bold font-playfair text-gray-900">
                    {{ __('booking.booking_history_title') }}</h2>
                <p class="text-lg text-gray-600 max-w-xl mx-auto mt-2">{{ __('booking.booking_history_subtitle') }}</p>
            </div>

            {{-- Booking Cards Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                @forelse ($bookings as $booking)
                    <div
                        class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        {{-- Card Header --}}
                        <div class="bg-black px-6 py-4">
                            <h3 class="text-white font-semibold text-lg">{{ $booking->name }}</h3>
                            <p class="text-blue-100 text-sm">{{ __('booking.booking_number') }} {{ $booking->id }}</p>
                        </div>

                        {{-- Card Body --}}
                        <div class="p-6">
                            {{-- Date and Time --}}
                            <div class="flex items-center mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center text-gray-600 mb-2">
                                        <i class="fas fa-calendar-alt mr-2 text-[#d4af37]"></i>
                                        <span class="font-medium">{{ $booking->date_time->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-clock mr-2 text-[#d4af37]"></i>
                                        <span class="font-medium">{{ $booking->date_time->format('H:i') }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="mb-4">
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            {{ $booking->status === 'pending'
                                                ? 'bg-yellow-100 text-yellow-700'
                                                : ($booking->status === 'completed'
                                                    ? 'bg-green-100 text-green-700'
                                                    : 'bg-gray-100 text-gray-600') }}">
                                        <i class="fas fa-circle mr-2 text-xs "></i>
                                        {{ __('booking.status_' . $booking->status) }}
                                    </span>

                                    {{-- Payment Status --}}
                                    @if (isset($booking->payment_status))
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            {{ $booking->payment_status === 'paid' || $booking->payment_status === 'settlement'
                                                ? 'bg-green-100 text-green-700'
                                                : ($booking->payment_status === 'pending'
                                                    ? 'bg-yellow-100 text-yellow-700'
                                                    : 'bg-red-100 text-red-700') }}">
                                            <i class="fas fa-money-bill-wave mr-1 text-xs"></i>
                                            {{ __('booking.payment_status_' . $booking->payment_status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Queue Number (if available) --}}
                            @if (isset($booking->queue_number))
                                <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-list-ol mr-2 text-[#d4af37]"></i>
                                        <span class="text-sm text-gray-600">{{ __('booking.queue_number') }}:</span>
                                        <span class="ml-2 font-bold text-[#d4af37]">#{{ $booking->queue_number }}</span>
                                    </div>
                                </div>
                            @endif

                            {{-- Action Buttons --}}
                            <div class="pt-4 border-t border-gray-100 space-y-2">
                                {{-- View Detail Button --}}
                                <a href="{{ route('bookings.show', $booking->id) }}"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 hover:bg-[#d4af37] bg-black text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                    <i class="fas fa-eye mr-2"></i>
                                    {{ __('booking.view_detail') }}
                                </a>

                            </div>
                        </div>
                    </div>
                @empty
                    {{-- Empty State --}}
                    <div class="text-center py-12">
                        <div class="max-w-md mx-auto">
                            <div class="mb-4">
                                <i class="fas fa-calendar-times text-6xl text-gray-300"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">{{ __('booking.no_bookings_title') }}</h3>
                            <p class="text-gray-500 mb-6">{{ __('booking.no_bookings_description') }}</p>
                            <a href="{{ route('bookings.create') }}"
                                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                {{ __('booking.create_new_booking') }}
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- SweetAlert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Success Alert Script --}}
    @if (session('booking_success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const translations = {
                    booking_success: @json(__('booking.booking_success')),
                    booking_created_for: @json(__('booking.booking_created_for')),
                    queue_number_label: @json(__('booking.queue_number_label')),
                    please_arrive_on_time: @json(__('booking.please_arrive_on_time')),
                    ok: @json(__('booking.ok'))
                };

                Swal.fire({
                    icon: 'success',
                    title: translations.booking_success,
                    html: `
                        <div class="text-center">
                            <p class="text-lg mb-2">${translations.booking_created_for}:</p>
                            <p class="font-bold text-xl text-[#d4af37] mb-3">{{ session('booking_success.name') }}</p>
                            <div class="bg-blue-50 rounded-lg p-4 mb-3">
                                <p class="text-sm text-gray-600 mb-1">${translations.queue_number_label}:</p>
                                <p class="text-3xl font-bold text-[#d4af37]">#{{ session('booking_success.queue_number') }}</p>
                            </div>
                            <p class="text-sm text-gray-500">${translations.please_arrive_on_time}</p>
                        </div>
                    `,
                    confirmButtonText: translations.ok,
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

    {{-- Custom Styles --}}
    <style>
        .card-hover:hover {
            transform: translateY(-2px);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
@endsection
