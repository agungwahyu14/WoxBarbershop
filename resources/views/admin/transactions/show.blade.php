@extends('admin.layouts.app')

@section('content')
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white">Transaction Detail</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">View complete transaction information</p>
            </div>
            <a href="{{ route('transactions.index') }}" class="btn bg-gray-200 text-gray-800 hover:bg-gray-300">
                <i class="mdi mdi-arrow-left mr-1"></i> Back
            </a>
        </div>
    </section>

    <section class="section main-section">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md space-y-6">
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 dark:text-white">Transaction Info</h2>
                    <p><strong>Order ID:</strong> {{ $transaction->order_id ?? '-' }}</p>
                    @php
                        $status = strtolower($transaction->transaction_status ?? '');
                        $badgeText = 'Tidak Diketahui';
                        $badgeClass = 'bg-gray-100 text-gray-800';

                        if ($status === 'pending') {
                            $badgeText = 'Menunggu';
                            $badgeClass = 'bg-yellow-100 text-yellow-800';
                        } elseif (in_array($status, ['cancel', 'failure', 'expire'])) {
                            $badgeText = 'Gagal';
                            $badgeClass = 'bg-red-100 text-red-800';
                        } elseif (in_array($status, ['settlement', 'capture'])) {
                            $badgeText = 'Sukses';
                            $badgeClass = 'bg-green-100 text-green-800';
                        }
                    @endphp

                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $badgeClass }}">
                        {{ $badgeText }}
                    </span>

                    <p><strong>Payment Type:</strong> {{ ucfirst($transaction->payment_type) ?? '-' }}</p>
                    <p><strong>Amount:</strong> Rp
                        {{ number_format($transaction->gross_amount ?? $transaction->total_amount, 0, ',', '.') }}</p>
                    <p><strong>Time:</strong>
                        {{ \Carbon\Carbon::parse($transaction->transaction_time)->format('d M Y H:i') }}</p>
                </div>
            </div>

        </div>
    </section>
@endsection
