<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Booking;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\MidtransService;

class MidtransController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        try {
            Config::$serverKey = config('services.midtrans.server_key');
            Config::$isProduction = config('services.midtrans.is_production', false);
            Config::$isSanitized = config('services.midtrans.is_sanitized', true);
            Config::$is3ds = config('services.midtrans.is_3ds', true);
        } catch (\Exception $e) {
            Log::error('Midtrans config error: ' . $e->getMessage());
        }
    }

    public function index()
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return redirect()->route('login');
            }

            // Ambil semua booking milik user
            $bookings = Booking::where('user_id', $user->id)
                    ->orderByDesc('id')
                    ->get();

            $serverKey = config('services.midtrans.server_key');
            $baseUrl = config('services.midtrans.is_production') ? 
                'https://api.midtrans.com/v2/' : 
                'https://api.sandbox.midtrans.com/v2/';

            $transactions = [];

            foreach ($bookings as $booking) {
                $orderId = $booking->id;

                try {
                    $response = Http::withBasicAuth($serverKey, '')
                        ->timeout(10)
                        ->get($baseUrl . $orderId . '/status');

                    if ($response->successful()) {
                        $data = $response->json();

                        $transactions[] = [
                            'order_id' => $orderId,
                            'amount' => $data['gross_amount'] ?? $booking->total_price,
                            'status' => $data['transaction_status'] ?? 'unknown',
                            'payment_type' => $data['payment_type'] ?? '-',
                            'transaction_time' => $data['transaction_time'] ?? $booking->created_at,
                            'booking' => $booking,
                            'formatted_payment_type' => $this->formatPaymentMethod($data['payment_type'] ?? '-'),
                            'formatted_status' => $this->formatStatus($data['transaction_status'] ?? 'unknown'),
                        ];
                    } else {
                        // Jika API gagal, tetap tampilkan dengan data dari database
                        $transactions[] = [
                            'order_id' => $orderId,
                            'amount' => $booking->total_price,
                            'status' => 'unknown',
                            'payment_type' => '-',
                            'transaction_time' => $booking->created_at,
                            'booking' => $booking,
                            'formatted_payment_type' => '-',
                            'formatted_status' => 'Status Tidak Diketahui',
                        ];
                    }
                } catch (\Exception $e) {
                    Log::error('Midtrans API error for booking ' . $orderId . ': ' . $e->getMessage());
                    
                    // Tetap tampilkan dengan data dari database
                    $transactions[] = [
                        'order_id' => $orderId,
                        'amount' => $booking->total_price,
                        'status' => 'unknown',
                        'payment_type' => '-',
                        'transaction_time' => $booking->created_at,
                        'booking' => $booking,
                        'formatted_payment_type' => '-',
                        'formatted_status' => 'Status Tidak Diketahui',
                    ];
                }
            }

            return view('transactions.index', compact('transactions'));

        } catch (\Exception $e) {
            Log::error('Transaction index error: ' . $e->getMessage());
            return view('transactions.index', ['transactions' => []]);
        }
    }
    

    /**
     * Generate ulang snap token untuk pembayaran pending
     */
   

    private function formatPaymentMethod($paymentType)
    {
        $paymentMethods = [
            'bank_transfer' => 'Bank Transfer',
            'credit_card' => 'Credit Card',
            'debit_card' => 'Debit Card',
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
            'dana' => 'DANA',
            'linkaja' => 'LinkAja',
            'ovo' => 'OVO',
            'qris' => 'QRIS',
            'bca_va' => 'BCA Virtual Account',
            'bni_va' => 'BNI Virtual Account',
            'bri_va' => 'BRI Virtual Account',
            'mandiri_va' => 'Mandiri Virtual Account',
            'permata_va' => 'Permata Virtual Account',
            'cstore' => 'Convenience Store',
            'akulaku' => 'Akulaku',
            'kredivo' => 'Kredivo',
            'echannel' => 'Mandiri Bill Payment',
            'bca_klikbca' => 'BCA KlikBCA',
            'bca_klikpay' => 'BCA KlikPay',
            'cimb_clicks' => 'CIMB Clicks',
            'danamon_online' => 'Danamon Online Banking',
            'indomaret' => 'Indomaret',
            'alfamart' => 'Alfamart',
        ];

        return $paymentMethods[$paymentType] ?? ucfirst(str_replace('_', ' ', $paymentType));
    }

    private function formatStatus($status)
    {
        $statusMap = [
            'pending' => 'Menunggu Pembayaran',
            'settlement' => 'Berhasil',
            'capture' => 'Berhasil',
            'deny' => 'Ditolak',
            'cancel' => 'Dibatalkan',
            'expire' => 'Kedaluwarsa',
            'failure' => 'Gagal',
            'refund' => 'Dikembalikan',
            'partial_refund' => 'Dikembalikan Sebagian',
            'authorize' => 'Diotorisasi',
            'unknown' => 'Status Tidak Diketahui',
        ];

        return $statusMap[$status] ?? ucfirst($status);
    }
}