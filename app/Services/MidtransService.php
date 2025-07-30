<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Transaction;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
                'gross_amount' => (int) $booking->total_price, // contoh
            ],
            'customer_details' => [
                'first_name' => $booking->name,
                'email' => $booking->user->email,
                'phone' => $booking->user->no_telepon,
            ]
        ];

        // Snap token akan digunakan di JavaScript
        $snapToken = Snap::getSnapToken($params);

        return $snapToken;
    }

}