<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;

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
            ]
        );

        return response()->json(['message' => 'Callback processed'], 200);
    }
}
