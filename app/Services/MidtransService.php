<?php

namespace App\Services;

use App\Models\Booking;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        // Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createTransaction(Booking $booking)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $booking->id,
                'gross_amount' => (int) $booking->total_price,
            ],
            'customer_details' => [
                'first_name' => $booking->name,
                'email' => $booking->user->email,
                'phone' => $booking->user->no_telepon,
            ],
        ];

        \App\Models\Transaction::updateOrCreate(
            ['order_id' => $booking->id],
            [
                'transaction_status' => 'pending',
                'payment_type' => null,
                'gross_amount' => $booking->total_price,
                'transaction_time' => now(),
                'bank' => null,
                'va_number' => null,
                'name' => $booking->name,
                'email' => $booking->user->email,
            ]
        );

        // Snap token akan digunakan di JavaScript
        $snapToken = Snap::getSnapToken($params);

        return $snapToken;
    }

    /**
     * Cancel transaction in Midtrans
     */
    public function cancelTransaction(Booking $booking)
    {
        try {
            // Cancel transaction via Midtrans API
            $cancel = \Midtrans\Transaction::cancel($booking->id);
            
            Log::info('Midtrans transaction cancelled', [
                'order_id' => $booking->id,
                'booking_id' => $booking->id,
                'status' => $cancel->transaction_status ?? 'cancelled'
            ]);

            // Update local transaction record
            \App\Models\Transaction::where('order_id', $booking->id)
                ->update([
                    'transaction_status' => 'cancel',
                    'updated_at' => now()
                ]);

            return true;

        } catch (\Midtrans\Exception $e) {
            Log::warning('Failed to cancel Midtrans transaction', [
                'order_id' => $booking->id,
                'error' => $e->getMessage()
            ]);

            // Even if Midtrans API call fails, update local record as cancelled
            \App\Models\Transaction::where('order_id', $booking->id)
                ->update([
                    'transaction_status' => 'cancel',
                    'updated_at' => now()
                ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Error cancelling transaction', [
                'order_id' => $booking->id,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }
}
