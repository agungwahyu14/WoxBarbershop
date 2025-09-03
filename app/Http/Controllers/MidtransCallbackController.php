<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('📥 Midtrans callback received:', $request->all());

        $orderId = $request->input('order_id');
        $transactionStatus = $request->input('transaction_status');
        $paymentType = $request->input('payment_type');
        $grossAmount = $request->input('gross_amount');
        $transactionTime = $request->input('transaction_time');

        // Optional: log VA / bank
        $bank = $request->input('va_numbers.0.bank') ?? $request->input('permata_va_number');
        $vaNumber = $request->input('va_numbers.0.va_number') ?? $request->input('permata_va_number');

        // Get additional customer data from request or find booking
        $customerName = $request->input('customer_details.first_name') ?? null;
        $customerEmail = $request->input('customer_details.email') ?? null;
        
        // If customer data not in callback, try to get from booking
        if (!$customerName || !$customerEmail) {
            try {
                $booking = \App\Models\Booking::with('user')->findOrFail($orderId);
                $customerName = $customerName ?? $booking->name;
                $customerEmail = $customerEmail ?? $booking->user->email;
            } catch (\Exception $e) {
                Log::warning('Could not fetch booking data for transaction', [
                    'order_id' => $orderId,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Update atau buat baru record transaksi
        $transaction = Transaction::updateOrCreate(
            ['order_id' => $orderId],
            [
                'transaction_status' => $transactionStatus,
                'payment_type' => $paymentType,
                'gross_amount' => $grossAmount,
                'transaction_time' => $transactionTime,
                'bank' => $bank,
                'va_number' => $vaNumber,
                'name' => $customerName,
                'email' => $customerEmail,
            ]
        );

        Log::info('📤 Transaction updated/created:', [
            'order_id' => $transaction->order_id,
            'transaction_status' => $transaction->transaction_status,
            'name' => $transaction->name,
            'email' => $transaction->email,
            'payment_type' => $transaction->payment_type,
            'gross_amount' => $transaction->gross_amount
        ]);

        return response()->json(['message' => 'Callback processed'], 200);
    }
}
