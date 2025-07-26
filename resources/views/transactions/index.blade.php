@extends('layouts.app')

@section('content')
    <!-- Header Section -->
    <section id="transaksi" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-playfair font-bold mb-4">Transaksi</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-8">
                    Lihat Transaksi Anda
                </p>
            </div>

            <!-- Main Content -->
            <div class="py-12 min-h-screen">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <!-- Stats Cards -->
                    <!-- Transactions List -->
                    <div class="space-y-6">
                        @foreach ($transactions as $transaction)
                            <div
                                class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                                <div class="p-6">
                                    <!-- Transaction Header -->
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-800 mb-1">
                                                Transaksi #{{ $transaction->id }}
                                            </h3>
                                            <div class="flex items-center space-x-2">
                                                <span
                                                    class="px-3 py-1 rounded-full text-xs font-medium
                                            @if ($transaction->status === 'paid') bg-green-100 text-green-800
                                            @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($transaction->status === 'confirmed') bg-blue-100 text-blue-800
                                            @elseif($transaction->status === 'failed') bg-red-100 text-red-800
                                            @elseif($transaction->status === 'processing') bg-purple-100 text-purple-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                                    @if ($transaction->status === 'paid')
                                                        Berhasil
                                                    @elseif($transaction->status === 'confirmed')
                                                        Dikonfirmasi
                                                    @else
                                                        {{ ucfirst($transaction->status) }}
                                                    @endif
                                                </span>
                                                <span class="text-sm text-gray-500">
                                                    {{ $transaction->created_at->format('d M Y H:i') }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="text-right">
                                            <p class="text-2xl font-bold text-gray-800">
                                                Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Transaction Details -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="p-2 bg-blue-50 rounded-lg">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-500">Booking</p>
                                                <p class="font-semibold text-gray-800">
                                                    {{ $transaction->booking->name ?? '-' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="flex items-center space-x-3">
                                            <div class="p-2 bg-green-50 rounded-lg">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-500">Customer</p>
                                                <p class="font-semibold text-gray-800">
                                                    {{ $transaction->booking->user->name ?? '-' }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center space-x-3">
                                            <div class="p-2 bg-purple-50 rounded-lg">
                                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-500">Payment Method</p>
                                                <p class="font-semibold text-gray-800">
                                                    @if ($transaction->payment_method === 'bank_transfer')
                                                        Bank Transfer
                                                    @elseif($transaction->payment_method === 'ewallet')
                                                        E-Wallet
                                                    @elseif($transaction->payment_method === 'cod')
                                                        Cash (COD)
                                                    @elseif($transaction->payment_method)
                                                        {{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}
                                                    @else
                                                        <span class="text-orange-600">Belum dipilih</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Additional Info for specific statuses -->
                                    @if ($transaction->status === 'confirmed' && $transaction->payment_method === 'cod')
                                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                    </path>
                                                </svg>
                                                <p class="text-sm text-blue-800 font-medium">
                                                    Metode pembayaran cash telah dikonfirmasi. Silakan bayar saat tiba di
                                                    lokasi.
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($transaction->status === 'pending' && $transaction->snap_token)
                                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <p class="text-sm text-yellow-800 font-medium">
                                                    Pembayaran
                                                    {{ $transaction->payment_method === 'bank_transfer' ? 'bank transfer' : 'e-wallet' }}
                                                    sedang menunggu.
                                                    <a href="{{ $transaction->redirect_url }}"
                                                        class="underline hover:no-underline">Lanjutkan pembayaran</a>
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Action Button -->
                                    @if ($transaction->status === 'pending' && !$transaction->payment_method)
                                        <div class="flex justify-end pt-4 border-t border-gray-100">
                                            <button type="button" data-id="{{ $transaction->id }}"
                                                class="inline-flex items-center px-6 py-3 bg-secondary text-white font-semibold rounded-lg hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg"
                                                onclick="openPaymentModal({{ $transaction->id }})">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                                    </path>
                                                </svg>
                                                Pilih Metode Pembayaran
                                            </button>
                                        </div>
                                    @elseif($transaction->status === 'pending' && $transaction->snap_token)
                                        <div class="flex justify-end pt-4 border-t border-gray-100">
                                            <a href="{{ $transaction->redirect_url }}"
                                                class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                    </path>
                                                </svg>
                                                Lanjutkan Pembayaran
                                            </a>
                                        </div>
                                    @elseif($transaction->status === 'confirmed' && $transaction->payment_method === 'cod')
                                        <div class="flex justify-end pt-4 border-t border-gray-100">
                                            <span
                                                class="inline-flex items-center px-6 py-3 bg-blue-100 text-blue-800 font-semibold rounded-lg">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Metode Cash Dikonfirmasi
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        @if ($transactions->isEmpty())
                            <div class="text-center py-16">
                                <div class="w-24 h-24 mx-auto mb-4 p-6 bg-gray-100 rounded-full">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum ada transaksi</h3>
                                <p class="text-gray-500">Transaksi Anda akan muncul di sini setelah melakukan pemesanan.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Payment Modal -->
    <div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4"
        style="backdrop-filter: blur(4px);">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-95 opacity-0"
            id="modalContent">
            <!-- Modal Header -->
            <div class="bg-secondary p-6 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                </path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-white">Pilih Metode Pembayaran</h2>
                    </div>
                    <button type="button" class="text-white hover:text-gray-200 transition-colors duration-200"
                        onclick="closePaymentModal()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <form id="paymentForm" method="POST" action="{{ route('payment.process') }}">
                    @csrf
                    <input type="hidden" name="transaction_id" id="transactionIdInput">

                    <label for="payment_method" class="block text-sm font-semibold text-gray-700 mb-3">
                        Metode Pembayaran:
                    </label>

                    <div class="space-y-3 mb-6">
                        <label
                            class="flex items-center p-4 border border-gray-200 rounded-xl hover:bg-gray-50 cursor-pointer transition-all duration-200 hover:border-blue-300 payment-option">
                            <input type="radio" name="payment_method" value="bank_transfer" class="sr-only" />
                            <div class="flex items-center space-x-3 w-full">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800">Bank Transfer</p>
                                    <p class="text-sm text-gray-500">Transfer melalui Virtual Account</p>
                                </div>
                                <div class="radio-circle w-4 h-4 border-2 border-gray-300 rounded-full"></div>
                            </div>
                        </label>

                        <label
                            class="flex items-center p-4 border border-gray-200 rounded-xl hover:bg-gray-50 cursor-pointer transition-all duration-200 hover:border-blue-300 payment-option">
                            <input type="radio" name="payment_method" value="ewallet" class="sr-only" />
                            <div class="flex items-center space-x-3 w-full">
                                <div class="p-2 bg-green-100 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800">E-Wallet</p>
                                    <p class="text-sm text-gray-500">GoPay, OVO, DANA, ShopeePay</p>
                                </div>
                                <div class="radio-circle w-4 h-4 border-2 border-gray-300 rounded-full"></div>
                            </div>
                        </label>

                        <label
                            class="flex items-center p-4 border border-gray-200 rounded-xl hover:bg-gray-50 cursor-pointer transition-all duration-200 hover:border-blue-300 payment-option">
                            <input type="radio" name="payment_method" value="cod" class="sr-only" />
                            <div class="flex items-center space-x-3 w-full">
                                <div class="p-2 bg-orange-100 rounded-lg">
                                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                        </path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800">Cash (COD)</p>
                                    <p class="text-sm text-gray-500">Bayar saat tiba di lokasi</p>
                                </div>
                                <div class="radio-circle w-4 h-4 border-2 border-gray-300 rounded-full"></div>
                            </div>
                        </label>
                    </div>

                    <div class="flex space-x-3">
                        <button type="button"
                            class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-200"
                            onclick="closePaymentModal()">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-3 bg-secondary text-white rounded-xl font-semibold hover:from-green-700 hover:to-green-800 transform hover:scale-105 transition-all duration-200 shadow-lg"
                            id="submitPaymentBtn" disabled>
                            Lanjutkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Custom Radio Button Styles */
        input[type="radio"]:checked+div .radio-circle {
            @apply border-blue-600 bg-blue-600;
        }

        input[type="radio"]:checked+div .radio-circle::after {
            content: '';
            @apply block w-2 h-2 bg-white rounded-full mx-auto mt-0.5;
        }

        input[type="radio"]:checked+div {
            @apply border-blue-300 bg-blue-50;
        }

        .payment-option.selected {
            @apply border-blue-300 bg-blue-50;
        }

        .payment-option.selected .radio-circle {
            @apply border-blue-600 bg-blue-600;
        }

        .payment-option.selected .radio-circle::after {
            content: '';
            @apply block w-2 h-2 bg-white rounded-full mx-auto mt-0.5;
        }
    </style>

    <script>
        function openPaymentModal(transactionId) {
            const modal = document.getElementById('paymentModal');
            const modalContent = document.getElementById('modalContent');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Animation
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);

            document.getElementById('transactionIdInput').value = transactionId;
        }

        function closePaymentModal() {
            const modal = document.getElementById('paymentModal');
            const modalContent = document.getElementById('modalContent');

            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');

                // Reset form
                document.getElementById('paymentForm').reset();
                document.querySelectorAll('.payment-option').forEach(option => {
                    option.classList.remove('selected');
                });
                document.getElementById('submitPaymentBtn').disabled = true;
            }, 300);
        }

        // Handle payment method selection
        document.addEventListener('DOMContentLoaded', function() {
            const paymentOptions = document.querySelectorAll('.payment-option');
            const submitBtn = document.getElementById('submitPaymentBtn');

            paymentOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Remove selected class from all options
                    paymentOptions.forEach(opt => opt.classList.remove('selected'));

                    // Add selected class to clicked option
                    this.classList.add('selected');

                    // Check the radio button
                    const radio = this.querySelector('input[type="radio"]');
                    radio.checked = true;

                    // Enable submit button
                    submitBtn.disabled = false;
                });
            });
        });

        // Close modal when clicking outside
        document.getElementById('paymentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePaymentModal();
            }
        });

        // Form submission with loading state
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitPaymentBtn');
            const selectedMethod = document.querySelector('input[name="payment_method"]:checked');

            if (!selectedMethod) {
                e.preventDefault();
                alert('Silakan pilih metode pembayaran terlebih dahulu.');
                return;
            }

            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses...
            `;
        });
    </script>
@endsection