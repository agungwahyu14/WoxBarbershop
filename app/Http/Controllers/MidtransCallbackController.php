<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Booking;
use App\Models\Loyalty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $bank = $request->input('va_numbers.0.bank') ?? $request->input('permata_va_number');
        $vaNumber = $request->input('va_numbers.0.va_number') ?? $request->input('permata_va_number');

        $customerName = $request->input('customer_details.first_name');
        $customerEmail = $request->input('customer_details.email');

        try {
            DB::beginTransaction();

            // 🔹 Dapatkan data booking untuk sinkronisasi
            $booking = Booking::with('user')->find($orderId);

            if ($booking) {
                if (!$customerName) $customerName = $booking->name;
                if (!$customerEmail) $customerEmail = $booking->user->email ?? null;
            }

            // 🔹 Simpan atau perbarui transaksi
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
                'payment_type' => $transaction->payment_type,
            ]);

            // 🔹 Update booking status & tambah loyalty point bila settlement
            if ($booking) {
                if (in_array($transactionStatus, ['settlement', 'capture'])) {
                    $booking->update([
                        'status' => 'completed',
                    ]);

                    // ✅ Tambahkan Loyalty Point
                    $user = $booking->user;
                    $loyalty = $user->loyalty;

                    if (!$loyalty) {
                        Log::info('🆕 Membuat record loyalty baru untuk user', ['user_id' => $user->id]);

                        $loyalty = Loyalty::create([
                            'user_id' => $user->id,
                            'points' => 0
                        ]);
                    }

                    $oldPoints = $loyalty->points;
                    $loyalty->addPoints(1);

                    Log::info('⭐ Loyalty point ditambahkan', [
                        'user_id' => $user->id,
                        'before' => $oldPoints,
                        'after' => $loyalty->points
                    ]);
                } elseif (in_array($transactionStatus, ['expire', 'cancel', 'deny'])) {
                    $booking->update([
                        'status' => 'cancelled',
                        'payment_status' => 'unpaid'
                    ]);
                }
            }

            DB::commit();

            return response()->json(['message' => 'Callback processed successfully'], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('❌ Failed to handle Midtrans callback', [
                'error' => $e->getMessage(),
                'order_id' => $orderId,
            ]);

            return response()->json(['error' => 'Failed to process callback'], 500);
        }
    }
}
