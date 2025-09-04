@extends('layouts.app')

@section('content')
    <section id="bookings" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            {{-- Header --}}
            <div class="text-center mb-12 mt-8">
                <h2 class="text-3xl md:text-4xl font-bold font-playfair text-gray-900">Detail Booking</h2>
                <p class="text-lg text-gray-600 max-w-xl mx-auto mt-2">Lihat dan kelola detail transaksi Anda.</p>
            </div>

            {{-- Booking Card --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="p-6 space-y-6">

                    {{-- Booking Overview --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                        {{-- Customer Name --}}
                        <div>
                            <h4 class="text-sm text-gray-500 uppercase mb-1">Pelanggan</h4>
                            <p class="text-xl font-semibold text-gray-800">
                                <i class="fas fa-user mr-2 text-blue-600"></i>
                                {{ $booking->name }}
                            </p>
                        </div>

                        {{-- Queue --}}
                        @if (isset($queuePosition) && $queuePosition)
                            <div>
                                <h4 class="text-sm text-gray-500 uppercase mb-1">Nomor Antrian</h4>
                                <p class="text-xl font-semibold text-blue-600">#{{ $queuePosition }}</p>
                                @if (isset($estimatedWaitTime) && $estimatedWaitTime > 0)
                                    <p class="text-sm text-gray-500 mt-1">Estimasi tunggu: {{ $estimatedWaitTime }} menit
                                    </p>
                                @endif
                            </div>
                        @endif

                        {{-- Tanggal Booking --}}
                        <div>
                            <h4 class="text-sm text-gray-500 uppercase mb-1">Tanggal Booking</h4>
                            <p class="text-xl font-semibold text-gray-800">
                                <i class="fas fa-calendar-alt mr-2 text-green-600"></i>
                                {{ $booking->date_time->format('d M Y') }}
                            </p>
                            <p class="text-sm text-gray-500">{{ $booking->date_time->format('H:i') }}</p>
                        </div>

                        {{-- Status --}}
                        <div>
                            <h4 class="text-sm text-gray-500 uppercase mb-1">Status</h4>
                            <span
                                class="inline-block px-3 py-1 rounded-full text-sm font-medium
                                @switch($booking->status)
                                    @case('pending')
                                        bg-yellow-100 text-yellow-700
                                        @break
                                    @case('confirmed')
                                        bg-blue-100 text-blue-700
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
                                @switch($booking->status)
                                    @case('pending')
                                        Menunggu
                                    @break

                                    @case('confirmed')
                                        Dikonfirmasi
                                    @break

                                    @case('completed')
                                        Selesai
                                    @break

                                    @case('cancelled')
                                        Dibatalkan
                                    @break

                                    @default
                                        {{ ucfirst($booking->status) }}
                                @endswitch
                            </span>
                        </div>

                        {{-- Payment Status & Total --}}
                        <div>
                            <h4 class="text-sm text-gray-500 uppercase mb-1">Pembayaran</h4>
                            <div class="space-y-1">
                                <span
                                    class="inline-block px-3 py-1 rounded-full text-sm font-medium
                                    @switch($booking->payment_status)
                                        @case('paid')
                                        @case('settlement')
                                            bg-green-100 text-green-700
                                            @break
                                        @case('pending')
                                            bg-yellow-100 text-yellow-700
                                            @break
                                        @case('unpaid')
                                        @case('failed')
                                            bg-red-100 text-red-700
                                            @break
                                        @default
                                            bg-gray-100 text-gray-600
                                    @endswitch">
                                    @switch($booking->payment_status)
                                        @case('paid')
                                        @case('settlement')
                                            Lunas
                                        @break

                                        @case('pending')
                                            Menunggu
                                        @break

                                        @case('unpaid')
                                            Belum Bayar
                                        @break

                                        @case('failed')
                                            Gagal
                                        @break

                                        @default
                                            {{ ucfirst($booking->payment_status) }}
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
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Detail Layanan</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @if (isset($booking->service))
                                    <div>
                                        <h5 class="text-sm text-gray-500 uppercase mb-1">Layanan</h5>
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
                                    <h5 class="text-sm text-gray-500 uppercase mb-1">Catatan</h5>
                                    <p class="text-gray-700">{{ $booking->description }}</p>
                                </div>
                            @endif

                            @if (isset($booking->description) && $booking->description)
                                <div class="mt-4">
                                    <h5 class="text-sm text-gray-500 uppercase mb-1">Gaya Rambut</h5>
                                    <p class="text-lg font-medium text-gray-800">{{ $booking->hairstyle->name }}</p>
                                    @if (isset($booking->hairstyle->price))
                                        <p class="text-sm text-gray-500">Rp
                                            {{ number_format($booking->hairstyle->price, 0, ',', '.') }}</p>
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
                            Kembali
                        </a>

                        {{-- Edit Booking (only if status allows) --}}
                        @if (in_array($booking->status, ['pending', 'confirmed']))
                            <a href="{{ route('bookings.edit', $booking->id) }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Booking
                            </a>
                        @endif

                        {{-- Cancel Booking (only if not completed or cancelled) --}}
                        @if (!in_array($booking->status, ['completed', 'cancelled']))
                            <button type="button"
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition duration-200"
                                onclick="confirmCancel({{ $booking->id }})">
                                <i class="fas fa-times mr-2"></i>
                                Batalkan
                            </button>

                            <form id="cancel-form-{{ $booking->id }}"
                                action="{{ route('bookings.destroy', $booking->id) }}" method="POST"
                                style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif

                        <script>
                            function confirmCancel(bookingId) {
                                Swal.fire({
                                    title: 'Apakah Anda yakin?',
                                    text: "Booking akan dibatalkan!",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#d33',
                                    cancelButtonColor: '#6c757d',
                                    confirmButtonText: 'Ya, batalkan!',
                                    cancelButtonText: 'Batal'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        document.getElementById('cancel-form-' + bookingId).submit();
                                    }
                                })
                            }
                        </script>

                        {{-- Payment Button (only show if booking is not completed and payment is not paid) --}}
                        @if (!in_array($booking->status, ['completed', 'cancelled']) && $booking->payment_status !== 'paid')
                            <button type="button" id="pay-button-{{ $booking->id }}"
                                onclick="initiatePayment({{ $booking->id }})"
                                class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                <i class="fas fa-credit-card mr-2"></i>
                                Bayar Sekarang
                            </button>
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
                                                console.log("Sukses", result);
                                                location.reload();
                                            },
                                            onPending: function(result) {
                                                console.log("Pending", result);
                                                location.reload();
                                            },
                                            onError: function(result) {
                                                console.error("Error", result);
                                            },
                                            onClose: function() {
                                                alert('Pembayaran dibatalkan.');
                                            }
                                        });
                                    });
                            }
                        </script>


                        {{-- Print Receipt (if completed) --}}
                        @if ($booking->status === 'completed')
                            <button onclick="printReceipt()"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition duration-200">
                                <i class="fas fa-print mr-2"></i>
                                Print Receipt
                            </button>
                        @endif

                        {{-- Create New Booking --}}
                        <a href="{{ route('dashboard') }}#reservasi"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Booking Baru
                        </a>

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
                    title: 'Berhasil!',
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
                    title: 'Oops!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#dc2626'
                });
            });
        </script>
    @endif


@endpush
