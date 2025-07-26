@extends('admin.layouts.app')

@section('content')
    <section class="is-hero-bar">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
            <div>
                <h1 class="title text-3xl font-bold text-gray-900 dark:text-white">
                    Edit Transaction
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    Update transaction details and status
                </p>
            </div>
            <div>
                <a href="{{ route('transactions.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition duration-150 ease-in-out">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Transactions
                </a>
            </div>
        </div>
    </section>

    <section class="section main-section">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Transaction Details</h3>
            </div>

            <div class="p-6">
                <!-- Transaction Info Card -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Transaction ID</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">#{{ $transaction->id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Customer</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $transaction->user->name ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                @if($transaction->service)
                                    {{ $transaction->service->name }}
                                @elseif($transaction->booking && $transaction->booking->service)
                                    {{ $transaction->booking->service->name }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Booking</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                @if($transaction->booking)
                                    Booking #{{ $transaction->booking->id }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Created At</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($transaction->created_at)->locale('id')->translatedFormat('d F Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Edit Form -->
                <form action="{{ route('transactions.update', $transaction->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Total Amount -->
                        <div>
                            <label for="total_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Total Amount <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 dark:text-gray-400">
                                    Rp
                                </span>
                                <input type="number" 
                                       id="total_amount" 
                                       name="total_amount" 
                                       value="{{ old('total_amount', $transaction->total_amount) }}"
                                       class="pl-8 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                                       step="0.01"
                                       min="0">
                            </div>
                            @error('total_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Payment Method
                            </label>
                            <select id="payment_method" 
                                    name="payment_method" 
                                    class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Payment Method</option>
                                <option value="cash" {{ old('payment_method', $transaction->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="bank_transfer" {{ old('payment_method', $transaction->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="e_wallet" {{ old('payment_method', $transaction->payment_method) == 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
                                <option value="credit_card" {{ old('payment_method', $transaction->payment_method) == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                            </select>
                            @error('payment_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Status -->
                        <div class="md:col-span-2">
                            <label for="payment_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Payment Status <span class="text-red-500">*</span>
                            </label>
                            <select id="payment_status" 
                                    name="payment_status" 
                                    class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                                    required>
                                <option value="">Select Status</option>
                                <option value="pending" {{ old('payment_status', $transaction->payment_status) == 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="confirmed" {{ old('payment_status', $transaction->payment_status) == 'confirmed' ? 'selected' : '' }}>
                                    Confirmed
                                </option>
                                <option value="paid" {{ old('payment_status', $transaction->payment_status) == 'paid' ? 'selected' : '' }}>
                                    Paid
                                </option>
                                <option value="settlement" {{ old('payment_status', $transaction->payment_status) == 'settlement' ? 'selected' : '' }}>
                                    Settlement
                                </option>
                                <option value="cancelled" {{ old('payment_status', $transaction->payment_status) == 'cancelled' ? 'selected' : '' }}>
                                    Cancelled
                                </option>
                                <option value="failed" {{ old('payment_status', $transaction->payment_status) == 'failed' ? 'selected' : '' }}>
                                    Failed
                                </option>
                                <option value="expire" {{ old('payment_status', $transaction->payment_status) == 'expire' ? 'selected' : '' }}>
                                    Expired
                                </option>
                            </select>
                            @error('payment_status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('transactions.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            <i class="fas fa-save mr-2"></i>
                            Update Transaction
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Success popup
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    // Error popup
    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
        });
    @endif

    // Format number input
    document.getElementById('total_amount').addEventListener('input', function(e) {
        let value = e.target.value;
        if (value < 0) {
            e.target.value = 0;
        }
    });
</script>
@endpush