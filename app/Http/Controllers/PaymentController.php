<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Transaction;
use App\Services\MidtransService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Midtrans\Snap;

class PaymentController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    public function pay(Request $request, $bookingId)
    {
        $booking = Booking::with('user')->findOrFail($bookingId);

        // Hanya izinkan jika status belum dibayar
        if (! in_array($booking->status, ['pending', 'confirmed']) || $booking->payment_status === 'paid') {
            return response()->json(['message' => 'Pembayaran tidak diperbolehkan.'], 403);
        }

        $snapToken = $this->midtransService->createTransaction($booking);

        if (! $snapToken) {
            return response()->json(['message' => 'Gagal membuat transaksi.'], 500);
        }

        // Simpan data transaksi awal ke database
        return response()->json([
            'snapToken' => $snapToken,
        ]);
    }

    public function showVA($orderId)
    {
        try {
            $status = \Midtrans\Transaction::status($orderId);

            if ($status->payment_type === 'bank_transfer') {
                $vaNumber = $status->va_numbers[0]->va_number ?? null;
                $bank = strtoupper($status->va_numbers[0]->bank ?? 'unknown');

                return view('payment.va_detail', [
                    'va_number' => $vaNumber,
                    'bank' => $bank,
                    'payment_type' => $status->payment_type,
                    'order_id' => $orderId,
                ]);
            }

            return back()->with('error', 'Bukan metode bank transfer');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengambil status transaksi: '.$e->getMessage());
        }
    }

    public function downloadReceipt($orderId)
    {
        try {
            // Get local transaction data first
            $localTransaction = \App\Models\Transaction::where('order_id', $orderId)->first();
            
            // Get booking data with relationships
            $booking = Booking::with(['user', 'service', 'hairstyle'])->find($orderId);
            
            if (!$booking) {
                return back()->with('error', 'Data booking tidak ditemukan');
            }

            // Prepare transaction data for the invoice
            $transactionData = [];
            
            if ($localTransaction) {
                // Use local transaction data
                $transactionData = [
                    'order_id' => $localTransaction->order_id,
                    'transaction_status' => $localTransaction->transaction_status,
                    'payment_type' => $localTransaction->payment_type,
                    'gross_amount' => $localTransaction->gross_amount,
                    'transaction_time' => $localTransaction->transaction_time,
                    'bank' => $localTransaction->bank,
                    'va_number' => $localTransaction->va_number,
                    'name' => $localTransaction->name,
                    'email' => $localTransaction->email,
                ];
            } else {
                // Fallback to Midtrans API if local transaction not found
                try {
                    $midtransTransaction = \Midtrans\Transaction::status($orderId);
                    $transactionData = [
                        'order_id' => $orderId,
                        'transaction_status' => $midtransTransaction->transaction_status ?? 'unknown',
                        'payment_type' => $midtransTransaction->payment_type ?? 'N/A',
                        'gross_amount' => $midtransTransaction->gross_amount ?? $booking->total_price,
                        'transaction_time' => $midtransTransaction->transaction_time ?? $booking->created_at,
                        'bank' => $midtransTransaction->va_numbers[0]->bank ?? null,
                        'va_number' => $midtransTransaction->va_numbers[0]->va_number ?? null,
                        'name' => $booking->name,
                        'email' => $booking->user->email,
                    ];
                } catch (\Exception $e) {
                    // If Midtrans API also fails, use booking data as fallback
                    $transactionData = [
                        'order_id' => $orderId,
                        'transaction_status' => $booking->payment_status === 'paid' ? 'settlement' : 'pending',
                        'payment_type' => 'N/A',
                        'gross_amount' => $booking->total_price,
                        'transaction_time' => $booking->created_at,
                        'bank' => null,
                        'va_number' => null,
                        'name' => $booking->name,
                        'email' => $booking->user->email,
                    ];
                }
            }

            // Generate PDF
            $pdf = Pdf::loadView('receipt.transaction', [
                'transaction' => $transactionData,
                'booking' => $booking
            ]);

            // Set PDF options
            $pdf->setPaper('A4', 'portrait');
            
            $fileName = 'Invoice_WOX_' . $orderId . '_' . date('Ymd') . '.pdf';

            return $pdf->download($fileName);
            
        } catch (\Exception $e) {
            Log::error('Download receipt error: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengunduh bukti pembayaran: ' . $e->getMessage());
        }
    }

    public function index()
    {
        try {
            $user = auth()->user();

            if (! $user) {
                return redirect()->route('login');
            }

            // Ambil semua booking milik user dengan transaksi lokal (dengan pagination)
            $bookings = Booking::with(['user', 'transaction'])
                ->where('user_id', $user->id)
                ->orderByDesc('id')
                ->get(); // Kita tetap pakai get() dulu karena perlu proses Midtrans API

            $serverKey = config('services.midtrans.server_key');
            $baseUrl = config('services.midtrans.is_production') ?
                'https://api.midtrans.com/v2/' :
                'https://api.sandbox.midtrans.com/v2/';

            $transactions = [];

            foreach ($bookings as $booking) {
                $orderId = $booking->id;

                // Check if we have local transaction data first
                $localTransaction = \App\Models\Transaction::where('order_id', $orderId)->first();

                if ($localTransaction) {
                    // Use local transaction data (includes name and email)
                    $transactions[] = [
                        'order_id' => $orderId,
                        'amount' => $localTransaction->gross_amount ?? $booking->total_price,
                        'status' => $localTransaction->transaction_status ?? 'unknown',
                        'payment_type' => $localTransaction->payment_type ?? 'unknown',
                        'channel' => $localTransaction->bank ?? '-',
                        'name' => $localTransaction->name ?? $booking->name, // From transaction table
                        'email' => $localTransaction->email ?? $booking->user->email, // From transaction table
                        'transaction_time' => $localTransaction->transaction_time ?? $booking->created_at,
                        'booking' => $booking,
                        'formatted_payment_type' => $this->formatPaymentMethod($localTransaction->payment_type ?? 'unknown'),
                        'formatted_status' => $this->formatStatus($localTransaction->transaction_status ?? 'unknown'),
                    ];
                } else {
                    // Fallback to Midtrans API if no local transaction
                    try {
                        $response = Http::withBasicAuth($serverKey, '')
                            ->timeout(10)
                            ->get($baseUrl.$orderId.'/status');

                        if ($response->successful()) {
                            $data = $response->json();

                            $transactions[] = [
                                'order_id' => $orderId,
                                'amount' => $data['gross_amount'] ?? $booking->total_price,
                                'status' => $data['transaction_status'] ?? 'unknown',
                                'payment_type' => $data['payment_type'] ?? '-',
                                'channel' => $data['channel'] ?? '-',
                                'name' => $booking->name, // From booking
                                'email' => $booking->user->email ?? '-', // From user relation
                                'transaction_time' => $data['transaction_time'] ?? $booking->created_at,
                                'booking' => $booking,
                                'formatted_payment_type' => $this->formatPaymentMethod($data['payment_type'] ?? '-'),
                                'formatted_status' => $this->formatStatus($data['transaction_status'] ?? 'unknown'),
                            ];
                        } else {
                            // Jika API gagal
                            $transactions[] = [
                                'order_id' => $orderId,
                                'amount' => $booking->total_price,
                                'status' => 'unknown',
                                'payment_type' => '-',
                                'channel' => '-',
                                'name' => $booking->name,
                                'email' => $booking->user->email ?? '-',
                                'transaction_time' => $booking->created_at,
                                'booking' => $booking,
                                'formatted_payment_type' => '-',
                                'formatted_status' => 'Status Tidak Diketahui',
                            ];
                        }
                    } catch (\Exception $e) {
                        Log::error('Midtrans API error for booking '.$orderId.': '.$e->getMessage());

                        // Jika terjadi exception
                        $transactions[] = [
                            'order_id' => $orderId,
                            'amount' => $booking->total_price,
                            'status' => 'unknown',
                            'payment_type' => '-',
                            'channel' => '-',
                            'name' => $booking->name,
                            'email' => $booking->user->email ?? '-',
                            'transaction_time' => $booking->created_at,
                            'booking' => $booking,
                            'formatted_payment_type' => '-',
                            'formatted_status' => 'Status Tidak Diketahui',
                        ];
                    }
                }
            }

            // Apply pagination to transactions collection
            $perPage = 6;
            $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
            $transactionsCollection = collect($transactions);
            
            $paginatedTransactions = new \Illuminate\Pagination\LengthAwarePaginator(
                $transactionsCollection->forPage($currentPage, $perPage)->values(),
                $transactionsCollection->count(),
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'pageName' => 'page']
            );

            return view('transactions.index', ['transactions' => $paginatedTransactions]);

        } catch (\Exception $e) {
            Log::error('Transaction index error: '.$e->getMessage());

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
            'cancelled' => 'Dibatalkan',
            'expire' => 'Kedaluwarsa',
            'failure' => 'Gagal',
            'refund' => 'Dikembalikan',
            'partial_refund' => 'Dikembalikan Sebagian',
            'authorize' => 'Diotorisasi',
            'unknown' => 'Status Tidak Diketahui',
        ];

        return $statusMap[$status] ?? ucfirst($status);
    }

    public function cashPayment(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'payment_method' => 'required|in:cash,bank',
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        // Buat transaksi baru
        $transaction = Transaction::create([
            'order_id' => $booking->id, // Menggunakan booking ID sebagai order_id
            'transaction_status' => 'pending',
            'payment_type' => $request->payment_method,
            'gross_amount' => $booking->total_price,
            'transaction_time' => now(),
            'bank' => null,
            'va_number' => null,
            'name' => $booking->name,
            'email' => $booking->user->email ?? null,
        ]);


        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dibuat.');
    }

    

    


    public function show($orderId)
    {
        try {
            // Ambil data booking terkait
            $booking = Booking::with('user')->findOrFail($orderId);
            
            // Check for local transaction first
            $localTransaction = \App\Models\Transaction::where('order_id', $orderId)->first();

            // Ambil status transaksi dari Midtrans
            $status = \Midtrans\Transaction::status($orderId);

            // Cek dan ambil data VA jika ada
            $vaNumber = null;
            $bank = null;

            if ($status->payment_type === 'bank_transfer' && isset($status->va_numbers[0])) {
                $vaNumber = $status->va_numbers[0]->va_number;
                $bank = strtoupper($status->va_numbers[0]->bank);
            }

            $data = [
                'order_id' => $status->order_id,
                'transaction_status' => $this->formatStatus($status->transaction_status),
                'payment_type' => $this->formatPaymentMethod($status->payment_type),
                'transaction_time' => $status->transaction_time,
                'amount' => $status->gross_amount,
                'va_number' => $vaNumber,
                'bank' => $bank,
                'booking' => $booking,
                // Add name and email from local transaction or fallback to booking/user
                'customer_name' => $localTransaction?->name ?? $booking->name ?? $booking->user->name,
                'customer_email' => $localTransaction?->email ?? $booking->user->email,
            ];

            return view('transactions.show', compact('data'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengambil detail transaksi: '.$e->getMessage());
        }
    }

    /**
     * Cancel transaction when booking is cancelled
     */
    public function cancelTransaction(Booking $booking)
{
    // Cek apakah transaksi sudah ada
    $transaction = Transaction::where('order_id', $booking->id)->first();

    if ($transaction) {
        $transaction->update([
            'transaction_status' => 'cancel',
        ]);
    } else {
        // Jika tidak ada transaksi, buat transaksi baru dengan status cancel
        Transaction::create([
            'order_id' => $booking->id,
            'transaction_status' => 'cancel',
            'payment_type' => $booking->payment_method,
            'gross_amount' => $booking->total_price,
            'transaction_time' => now(),
            'name' => $booking->name,
            'email' => $booking->user->email ?? null,
        ]);
    }

    return true; // bisa kamu ubah sesuai kebutuhan
}

}
