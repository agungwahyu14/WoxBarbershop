@extends('layouts.app')

@section('content')
    <section id="bookings" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            {{-- Header --}}
            <div class="text-center mb-10">
                <h2 class="text-3xl md:text-4xl font-bold font-playfair text-gray-900">Riwayat Booking</h2>
                <p class="text-lg text-gray-600 max-w-xl mx-auto mt-2">Lihat riwayat booking Anda di sini.</p>
            </div>

            {{-- Booking Cards Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                @forelse ($bookings as $booking)
                    <div
                        class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        {{-- Card Header --}}
                        <div class="bg-black px-6 py-4">
                            <h3 class="text-white font-semibold text-lg">{{ $booking->name }}</h3>
                            <p class="text-blue-100 text-sm">Booking #{{ $booking->id }}</p>
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
                                        {{ ucfirst($booking->status) }}
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
                                            {{ $booking->payment_status === 'unpaid' ? 'Belum Bayar' : ucfirst($booking->payment_status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Queue Number (if available) --}}
                            @if (isset($booking->queue_number))
                                <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-list-ol mr-2 text-[#d4af37]"></i>
                                        <span class="text-sm text-gray-600">Nomor Antrian:</span>
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
                                    Lihat Detail
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
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Booking</h3>
                            <p class="text-gray-500 mb-6">Anda belum memiliki riwayat booking. Mulai buat booking pertama
                                Anda!</p>
                            <a href="{{ route('bookings.create') }}"
                                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                Buat Booking Baru
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
                Swal.fire({
                    icon: 'success',
                    title: 'Booking Berhasil!',
                    html: `
                        <div class="text-center">
                            <p class="text-lg mb-2">Booking berhasil dibuat atas nama:</p>
                            <p class="font-bold text-xl text-[#d4af37] mb-3">{{ session('booking_success.name') }}</p>
                            <div class="bg-blue-50 rounded-lg p-4 mb-3">
                                <p class="text-sm text-gray-600 mb-1">Nomor Antrian Anda:</p>
                                <p class="text-3xl font-bold text-[#d4af37]">#{{ session('booking_success.queue_number') }}</p>
                            </div>
                            <p class="text-sm text-gray-500">Silakan datang sesuai jadwal yang telah ditentukan</p>
                        </div>
                    `,
                    confirmButtonText: 'OK',
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
