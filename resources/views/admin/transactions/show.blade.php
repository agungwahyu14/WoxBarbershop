@extends('admin.layouts.app')

@section('content')
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white">Transaction Detail</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">View complete transaction information</p>
            </div>
            <div class="flex items-center space-x-4 mt-4 md:mt-0">
                <a href="{{ route('admin.transactions.index') }}"
                    class="flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Transactions
                </a>
            </div>
        </div>
    </section>

    <section class="section main-section">
        <!-- Transaction Status Card -->
        <div class="mb-6">
            @php
                $status = strtolower($transaction->transaction_status ?? '');
                $badgeText = 'Tidak Diketahui';
                $badgeClass = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                $iconClass = 'fas fa-question-circle';
                $cardClass = 'border-gray-300 dark:border-gray-600';

                if ($status === 'pending') {
                    $badgeText = 'Menunggu Pembayaran';
                    $badgeClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
                    $iconClass = 'fas fa-clock';
                    $cardClass = 'border-yellow-300 dark:border-yellow-600';
                } elseif (in_array($status, ['cancel', 'failure', 'expire', 'deny'])) {
                    $badgeText = $status === 'cancel' ? 'Dibatalkan' : ($status === 'expire' ? 'Kedaluwarsa' : 'Gagal');
                    $badgeClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
                    $iconClass = 'fas fa-times-circle';
                    $cardClass = 'border-red-300 dark:border-red-600';
                } elseif (in_array($status, ['settlement', 'capture'])) {
                    $badgeText = 'Pembayaran Berhasil';
                    $badgeClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                    $iconClass = 'fas fa-check-circle';
                    $cardClass = 'border-green-300 dark:border-green-600';
                }
            @endphp

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border-2 {{ $cardClass }} p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 rounded-full {{ $badgeClass }}">
                            <i class="{{ $iconClass }} text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $badgeText }}</h2>
                            <p class="text-gray-600 dark:text-gray-400">Order ID: {{ $transaction->order_id ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">
                            Rp{{ number_format($transaction->gross_amount ?? $transaction->total_amount, 0, ',', '.') }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($transaction->transaction_time)->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction Details Grid -->
        <div class="grid lg:grid-cols-3 gap-6 mb-6">
            <!-- Customer Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg mr-3">
                        <i class="fas fa-user text-blue-600 dark:text-blue-300"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Customer Information</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Name</span>
                        <span
                            class="text-sm text-gray-900 dark:text-white font-medium">{{ $transaction->customer_name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Email</span>
                        <span class="text-sm text-gray-900 dark:text-white">{{ $transaction->customer_email ?? '-' }}</span>
                    </div>
                    
                </div>
            </div>

            <!-- Payment Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg mr-3">
                        <i class="fas fa-credit-card text-green-600 dark:text-green-300"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Payment Details</h3>
                </div>
                <div class="space-y-3">
                    @php
                        $paymentType = $transaction->payment_type ?? '';
                        $paymentMethod = '';

                        switch (strtolower($paymentType)) {
                            case 'credit_card':
                                $paymentMethod = 'Kartu Kredit';
                                break;
                            case 'bank_transfer':
                                $paymentMethod = 'Transfer Bank';
                                break;
                            case 'echannel':
                                $paymentMethod = 'Mandiri Bill';
                                break;
                            case 'bca_va':
                                $paymentMethod = 'BCA Virtual Account';
                                break;
                            case 'bni_va':
                                $paymentMethod = 'BNI Virtual Account';
                                break;
                            case 'bri_va':
                                $paymentMethod = 'BRI Virtual Account';
                                break;
                            case 'permata':
                                $paymentMethod = 'Permata Virtual Account';
                                break;
                            case 'gopay':
                                $paymentMethod = 'GoPay';
                                break;
                            case 'shopeepay':
                                $paymentMethod = 'ShopeePay';
                                break;
                            case 'qris':
                                $paymentMethod = 'QRIS';
                                break;
                            case 'indomaret':
                                $paymentMethod = 'Indomaret';
                                break;
                            case 'alfamart':
                                $paymentMethod = 'Alfamart';
                                break;
                            default:
                                $paymentMethod = ucwords(str_replace('_', ' ', $paymentType));
                        }
                    @endphp

                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Method</span>
                        <span class="text-sm text-gray-900 dark:text-white font-medium">{{ $paymentMethod }}</span>
                    </div>

                    @if ($transaction->va_number ?? false)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">VA Number</span>
                            <span
                                class="text-sm text-gray-900 dark:text-white font-mono">{{ $transaction->va_number }}</span>
                        </div>
                    @endif

                    @if ($transaction->biller_code ?? false)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Biller Code</span>
                            <span
                                class="text-sm text-gray-900 dark:text-white font-mono">{{ $transaction->biller_code }}</span>
                        </div>
                    @endif

                    @if ($transaction->bill_key ?? false)
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Bill Key</span>
                            <span
                                class="text-sm text-gray-900 dark:text-white font-mono">{{ $transaction->bill_key }}</span>
                        </div>
                    @endif

                    @if ($transaction->fraud_status ?? false)
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Fraud Status</span>
                            @php
                                $fraudStatus = strtolower($transaction->fraud_status);
                                $fraudClass =
                                    $fraudStatus === 'accept'
                                        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                        : ($fraudStatus === 'challenge'
                                            ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
                                            : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200');
                            @endphp
                            <span class="px-2 py-1 text-xs rounded-full {{ $fraudClass }}">
                                {{ ucfirst($transaction->fraud_status) }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Transaction Timeline -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg mr-3">
                        <i class="fas fa-history text-purple-600 dark:text-purple-300"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Transaction Timeline</h3>
                </div>
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mt-1.5"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Transaction Created</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($transaction->transaction_time)->format('d M Y, H:i:s') }}
                            </p>
                        </div>
                    </div>

                    @if ($transaction->settlement_time ?? false)
                        <div class="flex items-start space-x-3">
                            <div class="w-3 h-3 bg-green-500 rounded-full mt-1.5"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Payment Settled</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($transaction->settlement_time)->format('d M Y, H:i:s') }}
                                </p>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-start space-x-3">
                        <div
                            class="w-3 h-3 {{ in_array($status, ['settlement', 'capture']) ? 'bg-green-500' : (in_array($status, ['cancel', 'failure', 'expire']) ? 'bg-red-500' : 'bg-yellow-500') }} rounded-full mt-1.5">
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Current Status</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ $badgeText }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        {{-- <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center mb-4">
                <div class="p-2 bg-indigo-100 dark:bg-indigo-900 rounded-lg mr-3">
                    <i class="fas fa-info-circle text-indigo-600 dark:text-indigo-300"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Additional Information</h3>
            </div>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Transaction ID</span>
                        <span
                            class="text-sm text-gray-900 dark:text-white font-mono">{{ $transaction->transaction_id ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Merchant ID</span>
                        <span class="text-sm text-gray-900 dark:text-white">{{ $transaction->merchant_id ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Currency</span>
                        <span
                            class="text-sm text-gray-900 dark:text-white">{{ strtoupper($transaction->currency ?? 'IDR') }}</span>
                    </div>
                </div>
                <div class="space-y-3">
                    @if ($transaction->signature_key ?? false)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Signature</span>
                            <span
                                class="text-sm text-gray-900 dark:text-white font-mono">{{ substr($transaction->signature_key, 0, 16) }}...</span>
                        </div>
                    @endif

                    @if ($transaction->status_code ?? false)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Status Code</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $transaction->status_code }}</span>
                        </div>
                    @endif

                    @if ($transaction->status_message ?? false)
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Status Message</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $transaction->status_message }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div> --}}

        <!-- Actions -->
        @if (in_array($status, ['pending']))
            <div class="mt-6 flex justify-end space-x-4">
                <button onclick="confirmAction('cancel')"
                    class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200">
                    <i class="fas fa-times mr-2"></i>Cancel Transaction
                </button>
                <button onclick="refreshStatus()"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                    <i class="fas fa-sync-alt mr-2"></i>Refresh Status
                </button>
            </div>
        @endif
    </section>
@endsection

@push('scripts')
    <script></script>
@endpush

@push('styles')
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .timeline-dot {
            position: relative;
        }

        .timeline-dot::after {
            content: '';
            position: absolute;
            left: 50%;
            top: 100%;
            width: 2px;
            height: 20px;
            background: #e5e7eb;
            transform: translateX(-50%);
        }

        .timeline-dot:last-child::after {
            display: none;
        }
    </style>
@endpush
