@extends('admin.layouts.app')

@section('content')
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white"> <i class="fas fa-receipt mr-3"></i>
                    {{ __('admin.transaction_detail') }}</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ __('admin.transaction_detail_subtitle') }}</p>
            </div>

        </div>
    </section>

    <section class="section min-h-screen main-section">
        <!-- Transaction Status Card -->
        <div class="mb-6">
            @php
                $status = strtolower($transaction->transaction_status ?? '');
                $badgeText = __('admin.unknown_status');
                $badgeClass = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                $iconClass = 'fas fa-question-circle';
                $cardClass = 'border-gray-300 dark:border-gray-600';

                if ($status === 'pending') {
                    $badgeText = __('admin.pending_payment');
                    $badgeClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
                    $iconClass = 'fas fa-clock';
                    $cardClass = 'border-yellow-300 dark:border-yellow-600';
                } elseif (in_array($status, ['cancel', 'failure', 'expire', 'deny'])) {
                    $badgeText =
                        $status === 'cancel'
                            ? __('admin.cancelled')
                            : ($status === 'expire'
                                ? __('admin.expired')
                                : __('admin.failed'));
                    $badgeClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
                    $iconClass = 'fas fa-times-circle';
                    $cardClass = 'border-red-300 dark:border-red-600';
                } elseif (in_array($status, ['settlement', 'capture'])) {
                    $badgeText = __('admin.payment_success');
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
        <div class="grid lg:grid-cols-4 gap-6 mb-6">
            <!-- Customer Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg mr-3">
                        <i class="fas fa-user text-blue-600 dark:text-blue-300"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('admin.customer_information') }}
                    </h3>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('admin.name') }}</span>
                        <span
                            class="text-sm text-gray-900 dark:text-white font-medium">{{ $transaction->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('admin.email') }}</span>
                        <span class="text-sm text-gray-900 dark:text-white">{{ $transaction->email ?? '-' }}</span>
                    </div>

                </div>
            </div>

            <!-- Payment Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg mr-3">
                        <i class="fas fa-credit-card text-green-600 dark:text-green-300"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('admin.payment_details') }}</h3>
                </div>
                <div class="space-y-3">
                    @php
                        $paymentType = $transaction->payment_type ?? '';
                        $paymentMethod = '';

                        switch (strtolower($paymentType)) {
                            case 'credit_card':
                                $paymentMethod = __('admin.credit_card');
                                break;
                            case 'bank_transfer':
                                $paymentMethod = __('admin.bank_transfer');
                                break;
                            case 'echannel':
                                $paymentMethod = __('admin.mandiri_bill');
                                break;
                            case 'bca_va':
                                $paymentMethod = __('admin.bca_virtual_account');
                                break;
                            case 'bni_va':
                                $paymentMethod = __('admin.bni_virtual_account');
                                break;
                            case 'bri_va':
                                $paymentMethod = __('admin.bri_virtual_account');
                                break;
                            case 'permata':
                                $paymentMethod = __('admin.permata_virtual_account');
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
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('admin.method') }}</span>
                        <span class="text-sm text-gray-900 dark:text-white font-medium">{{ $paymentMethod }}</span>
                    </div>

                    @if ($transaction->va_number ?? false)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span
                                class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('admin.va_number') }}</span>
                            <span
                                class="text-sm text-gray-900 dark:text-white font-mono">{{ $transaction->va_number }}</span>
                        </div>
                    @endif

                    @if ($transaction->biller_code ?? false)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span
                                class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('admin.biller_code') }}</span>
                            <span
                                class="text-sm text-gray-900 dark:text-white font-mono">{{ $transaction->biller_code }}</span>
                        </div>
                    @endif

                    @if ($transaction->bill_key ?? false)
                        <div class="flex justify-between items-center py-2">
                            <span
                                class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('admin.bill_key') }}</span>
                            <span
                                class="text-sm text-gray-900 dark:text-white font-mono">{{ $transaction->bill_key }}</span>
                        </div>
                    @endif

                    @if ($transaction->fraud_status ?? false)
                        <div class="flex justify-between items-center py-2">
                            <span
                                class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('admin.fraud_status') }}</span>
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
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('admin.transaction_timeline') }}
                    </h3>
                </div>
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mt-1.5"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ __('admin.transaction_created') }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($transaction->transaction_time)->format('d M Y, H:i:s') }}
                            </p>
                        </div>
                    </div>

                    @if ($transaction->settlement_time ?? false)
                        <div class="flex items-start space-x-3">
                            <div class="w-3 h-3 bg-green-500 rounded-full mt-1.5"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ __('admin.payment_settled') }}</p>
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
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('admin.current_status') }}
                            </p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ $badgeText }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-cogs mr-3 text-green-600"></i>
                        {{ __('admin.actions_column') }}
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <!-- Back to Transactions Button -->
                    <a href="{{ route('admin.transactions.index') }}"
                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        {{ __('admin.back_to_transactions') }}
                    </a>

                    <!-- Action Buttons for Pending Transactions -->
                    @if (in_array($status, ['pending']))
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex flex-col sm:flex-row justify-end gap-3">
                                <!-- Cancel Button -->
                                <button onclick="confirmAction('cancel')"
                                    class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200 flex items-center justify-center">
                                    <i class="fas fa-times mr-2"></i>{{ __('admin.cancel_transaction') }}
                                </button>

                                <!-- Conditional Buttons Based on Payment Type -->
                                @if ($transaction->payment_type == 'cash')
                                    <!-- Mark as Paid Button for Cash Payments -->
                                    <button onclick="confirmSettlement({{ $transaction->id }})"
                                        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-200 flex items-center justify-center">
                                        <i class="fas fa-check mr-2"></i>{{ __('admin.confirm_settlement') }}
                                    </button>
                                @else
                                    <!-- Refresh Status Button for Non-Cash Payments -->
                                    <button onclick="refreshStatus()"
                                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 flex items-center justify-center">
                                        <i class="fas fa-sync-alt mr-2"></i>{{ __('admin.refresh_status') }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions -->

    </section>
@endsection

@push('scripts')
    <script>
        // Translation variables
        const confirmText = '{{ __('admin.confirm') }}';
        const cancelText = '{{ __('admin.cancel') }}';
        const yesText = '{{ __('admin.yes') }}';
        const noText = '{{ __('admin.no') }}';
        const successText = '{{ __('admin.success') }}';
        const errorText = '{{ __('admin.error') }}';
        const processingText = '{{ __('admin.processing') }}';
        const cancelConfirmation = '{{ __('admin.cancel_transaction_confirmation') }}';
        const cancelWarning = '{{ __('admin.cancel_transaction_warning') }}';
        const refreshConfirmation = '{{ __('admin.refresh_status_confirmation') }}';
        const refreshInfo = '{{ __('admin.refresh_status_info') }}';
        const confirmSettlementText = '{{ __('admin.confirm_settlement') }}';
        const settlementWarning = '{{ __('admin.settlement_warning') }}';
        const yesSettlement = '{{ __('admin.yes_settlement') }}';
        const processingSettlement = '{{ __('admin.processing_settlement') }}';

        // Show notification function
        function showNotification(type, title, message) {
            Swal.fire({
                icon: type,
                title: title,
                text: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }

        // Confirm action function
        function confirmAction(action) {
            if (action === 'cancel') {
                cancelTransaction();
            }
        }

        // Cancel transaction function
        function cancelTransaction() {
            Swal.fire({
                title: confirmText,
                html: `${cancelConfirmation}<br><small class="text-red-600">${cancelWarning}</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: yesText + ', ' + '{{ __('admin.cancel_transaction') }}',
                cancelButtonText: noText,
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: processingText,
                        html: '{{ __('admin.cancelling_transaction') }}...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    // Send cancel request
                    fetch('{{ route('admin.transactions.cancel', $transaction->id) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.close();

                            if (data.success) {
                                showNotification('success', successText, data.message);
                                // Reload page after 2 seconds
                                setTimeout(() => {
                                    window.location.reload();
                                }, 2000);
                            } else {
                                showNotification('error', errorText, data.message);
                            }
                        })
                        .catch(error => {
                            Swal.close();
                            console.error('Error:', error);
                            showNotification('error', errorText, '{{ __('admin.error_occurred') }}');
                        });
                }
            });
        }

        // Confirm settlement function
        function confirmSettlement(transactionId) {
            Swal.fire({
                title: confirmSettlementText,
                text: settlementWarning,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#22c55e',
                cancelButtonColor: '#6b7280',
                confirmButtonText: yesSettlement,
                cancelButtonText: cancelText,
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: processingText,
                        html: processingSettlement,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    // Send settlement request
                    fetch(`/admin/transactions/${transactionId}/settlement`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.close();

                            if (data.success) {
                                showNotification('success', successText, data.message);
                                // Reload page after 2 seconds
                                setTimeout(() => {
                                    window.location.reload();
                                }, 2000);
                            } else {
                                showNotification('error', errorText, data.message);
                            }
                        })
                        .catch(error => {
                            Swal.close();
                            console.error('Error:', error);
                            showNotification('error', errorText, '{{ __('admin.error_occurred') }}');
                        });
                }
            });
        }

        // Refresh status function
        function refreshStatus() {
            Swal.fire({
                title: confirmText,
                html: `${refreshConfirmation}<br><small class="text-blue-600">${refreshInfo}</small>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#6b7280',
                confirmButtonText: yesText + ', ' + '{{ __('admin.refresh_status') }}',
                cancelButtonText: noText
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: processingText,
                        html: '{{ __('admin.refreshing_status') }}...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    // Send refresh request
                    fetch('{{ route('admin.transactions.refresh-status', $transaction->id) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.close();

                            if (data.success) {
                                showNotification('success', successText, data.message);
                                // Reload page after 2 seconds
                                setTimeout(() => {
                                    window.location.reload();
                                }, 2000);
                            } else {
                                showNotification('error', errorText, data.message);
                            }
                        })
                        .catch(error => {
                            Swal.close();
                            console.error('Error:', error);
                            showNotification('error', errorText, '{{ __('admin.error_occurred') }}');
                        });
                }
            });
        }
    </script>
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
