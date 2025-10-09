@extends('layouts.app')

@section('content')
    <section id="transactions" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            {{-- Header --}}
            <div class="text-center mb-10 mt-8">
                <h2 class="text-3xl md:text-4xl font-bold font-playfair text-gray-900">
                    {{ __('transactions.transaction_history') }}</h2>
                <p class="text-lg text-gray-600 max-w-xl mx-auto mt-2">{{ __('transactions.view_all_transactions') }}</p>
            </div>

            @if (count($transactions) > 0)
                {{-- Transaction Cards Grid --}}
                <div id="transactionGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    @foreach ($transactions as $tx)
                        <div
                            class="transaction-card bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">

                            {{-- Card Header --}}
                            <div class="bg-black px-6 py-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-white font-semibold text-lg">Transaksi #{{ $tx['order_id'] }}</h3>
                                        <p class="text-blue-100 text-sm">
                                            {{ \Carbon\Carbon::parse($tx['transaction_time'])->format('d M Y H:i') }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-white font-bold text-xl">Rp
                                            {{ number_format($tx['amount'], 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-6">

                                {{-- Customer Information --}}
                                <div class="mb-4 pb-4 border-b border-gray-100">
                                    <h4 class="text-sm font-medium text-gray-900 mb-2">
                                        {{ __('transactions.customer_information') }}</h4>
                                    <div class="space-y-1">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">Nama:</span>
                                            <span class="text-sm font-medium text-gray-900">{{ $tx['name'] ?? '-' }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">Email:</span>
                                            <span class="text-sm text-gray-700">{{ $tx['email'] ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Payment Method --}}
                                <div class="mb-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Metode Pembayaran:</span>
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <span>{{ $tx['formatted_payment_type'] }}</span>
                                        </span>
                                    </div>
                                </div>

                                {{-- Status --}}
                                <div class="mb-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Status:</span>
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                            @switch($tx['status'])
                                                @case('settlement')
                                                @case('capture')
                                                    bg-green-100 text-green-800
                                                    @break
                                                @case('pending')
                                                    bg-yellow-100 text-yellow-800
                                                    @break
                                                @case('deny')
                                                @case('failure')
                                                @case('expire')
                                                    bg-red-100 text-red-800
                                                    @break
                                                @case('cancel')
                                                    bg-gray-100 text-gray-800
                                                    @break
                                                @case('refund')
                                                @case('partial_refund')
                                                    bg-blue-100 text-blue-800
                                                    @break
                                                @default
                                                    bg-gray-100 text-gray-800
                                            @endswitch
                                        ">
                                            {{ $tx['formatted_status'] }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="pt-4 border-t border-gray-100 space-y-2">
                                    {{-- View Detail Button --}}

                                    @if ($tx['payment_type'] == 'bank_transfer')
                                        <a href="{{ route('payment.show', $tx['order_id']) }}"
                                            class="w-full inline-flex justify-center items-center px-4 py-2 hover:bg-blue-600 bg-blue-500 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                            <i class="fas fa-eye mr-2"></i>
                                            Lihat Detail
                                        </a>
                                    @endif

                                    {{-- Payment Action for Pending Status --}}
                                    {{-- @if ($tx['status'] == 'pending')
                                        <button
                                            class="btn-bayar-sekarang w-full inline-flex justify-center items-center px-4 py-2 hover:bg-orange-600 bg-orange-500 text-white text-sm font-medium rounded-lg transition-colors duration-200"
                                            data-order-id="{{ $tx['order_id'] }}">
                                            <i class="fas fa-credit-card mr-2"></i>
                                            Bayar Sekarang
                                        </button>
                                    @endif --}}

                                    {{-- Download Receipt for Successful Transactions --}}
                                    @if (in_array($tx['status'], ['settlement', 'capture']))
                                        <a href="{{ route('transaction.download', $tx['order_id']) }}" target="_blank"
                                            class="w-full inline-flex justify-center items-center px-4 py-2 hover:bg-green-600 bg-green-500 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                            <i class="fas fa-download mr-2"></i>
                                            Unduh Invoice
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-12">
                    <div class="max-w-md mx-auto">
                        <div class="mb-4">
                            <i class="fas fa-receipt text-6xl text-gray-300"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">{{ __('transactions.no_transactions') }}</h3>
                        <p class="text-gray-500">{{ __('transactions.no_transaction_history') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Show success message if exists
            @if (session('success'))
                Swal.fire({
                    title: @json(__('transactions.success')),
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonText: @json(__('transactions.ok')),
                    customClass: {
                        popup: 'rounded-lg',
                        confirmButton: 'bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600'
                    },
                    buttonsStyling: false
                });
            @endif

            // Show error message if exists
            @if (session('error'))
                Swal.fire({
                    title: @json(__('transactions.error')),
                    text: '{{ session('error') }}',
                    icon: 'error',
                    confirmButtonText: @json(__('transactions.ok')),
                    customClass: {
                        popup: 'rounded-lg',
                        confirmButton: 'bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600'
                    },
                    buttonsStyling: false
                });
            @endif

            const bayarButtons = document.querySelectorAll(".btn-bayar-sekarang");

            bayarButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const orderId = button.dataset.orderId;

                    fetch(`/transaction/va/${orderId}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.payment_type === 'bank_transfer' && data.va_number) {
                                Swal.fire({
                                    title: @json(__('transactions.virtual_account_payment')),
                                    html: `
            <p class="mb-2">${@json(__('transactions.transfer_instruction'))}</p>
            <div style="background-color:#f1f1f1;padding:10px;border-radius:8px;margin-bottom:10px">
                <strong>${@json(__('transactions.bank'))}:</strong> ${data.bank}<br>
                <strong>${@json(__('transactions.va_number'))}:</strong><br>
                <span style="font-size:1.5em;font-weight:bold;">${data.va_number}</span>
            </div>
        `,
                                    icon: 'info',
                                    confirmButtonText: @json(__('transactions.close')),
                                    customClass: {
                                        popup: 'rounded-lg',
                                        confirmButton: 'bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600'
                                    },
                                    buttonsStyling: false
                                });
                            } else if (data.payment_type === 'qris' && data.qr_url) {
                                Swal.fire({
                                    title: @json(__('transactions.qris_payment')),
                                    html: `
            <p class="mb-2">${@json(__('transactions.scan_qr_instruction'))}</p>
            <img src="${data.qr_url}" alt="QRIS" class="mx-auto rounded shadow-md" style="max-width: 250px;">
        `,
                                    icon: 'info',
                                    confirmButtonText: @json(__('transactions.close')),
                                    customClass: {
                                        popup: 'rounded-lg',
                                        confirmButton: 'bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600'
                                    },
                                    buttonsStyling: false
                                });
                            } else if (['gopay', 'shopeepay', 'other_e_wallets'].includes(data
                                    .payment_type) && data.redirect_url) {
                                Swal.fire({
                                    title: @json(__('transactions.continue_payment')),
                                    html: `<p>${@json(__('transactions.complete_payment_instruction'))} ${data.payment_type.toUpperCase()}.</p>`,
                                    icon: 'info',
                                    showCancelButton: true,
                                    confirmButtonText: @json(__('transactions.pay_now')),
                                    cancelButtonText: @json(__('transactions.cancel')),
                                    customClass: {
                                        popup: 'rounded-lg',
                                        confirmButton: 'bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600',
                                        cancelButton: 'px-4 py-2 rounded border border-gray-300'
                                    },
                                    buttonsStyling: false
                                }).then(result => {
                                    if (result.isConfirmed) {
                                        window.open(data.redirect_url, '_blank');
                                    }
                                });
                            } else {
                                Swal.fire(@json(__('transactions.info')),
                                    @json(__('transactions.payment_method_not_supported')),
                                    'info');
                            }
                        })
                        .catch(err => {
                            Swal.fire(@json(__('transactions.error')),
                                @json(__('transactions.failed_to_get_va_data')), 'error');
                        });
                });
            });
        });
    </script>
@endpush
