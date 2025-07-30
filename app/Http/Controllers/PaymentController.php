<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Services\MidtransService;
use Midtrans\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;

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
        if (!in_array($booking->status, ['pending', 'confirmed']) || $booking->payment_status === 'paid') {
            return response()->json(['message' => 'Pembayaran tidak diperbolehkan.'], 403);
        }

        $snapToken = $this->midtransService->createTransaction($booking);

        if (!$snapToken) {
            return response()->json(['message' => 'Gagal membuat transaksi.'], 500);
        }

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

            return response()->json([
                'va_number' => $vaNumber,
                'bank' => $bank,
                'payment_type' => $status->payment_type,
            ]);
        }

        return response()->json([
            'message' => 'Bukan metode bank transfer',
        ], 400);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Gagal mengambil status transaksi',
            'details' => $e->getMessage(),
        ], 500);
    }
}

public function downloadReceipt($orderId)
{
    try {
        $transaction = \Midtrans\Transaction::status($orderId);

        $pdf = Pdf::loadView('receipt.transaction', ['transaction' => $transaction]);
        return $pdf->download('bukti_transaksi_' . $orderId . '.pdf');
    } catch (\Exception $e) {
        return back()->with('error', 'Gagal mengambil data transaksi');
    }
}


}
