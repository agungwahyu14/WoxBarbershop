<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\MidtransService;
use App\Services\NotificationService;
use App\Http\Requests\TransactionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $midtransService;
    protected $notificationService;

    public function __construct(
        MidtransService $midtransService,
        NotificationService $notificationService
    ) {
        $this->midtransService = $midtransService;
        $this->notificationService = $notificationService;
        
        $this->middleware('auth');
        $this->middleware('throttle:payment,5,1')->only(['processPayment', 'createMidtransPayment']);
    }

    /**
     * Process payment based on selected method
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'payment_method' => 'required|in:bank_transfer,ewallet,cod'
        ]);

        try {
            DB::beginTransaction();

            $transaction = Transaction::with('booking.user')->findOrFail($request->transaction_id);
            
            // Check if user owns this transaction
            if ($transaction->booking->user_id !== auth()->id()) {
                return back()->with('error', 'Unauthorized access to transaction.');
            }

            // Check if transaction is still pending
            if ($transaction->status !== 'pending') {
                return back()->with('error', 'Transaction sudah diproses sebelumnya.');
            }

            // Update payment method
            $transaction->update([
                'payment_method' => $request->payment_method
            ]);

            // Process based on payment method
            if (in_array($request->payment_method, ['bank_transfer', 'ewallet'])) {
                // Use Midtrans for online payments
                return $this->processMidtransPayment($transaction);
            } else {
                // Handle cash payment
                return $this->processCashPayment($transaction);
            }

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Error processing payment', [
                'transaction_id' => $request->transaction_id,
                'payment_method' => $request->payment_method,
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Process Midtrans payment (bank transfer, e-wallet)
     */
    private function processMidtransPayment(Transaction $transaction)
    {
        try {
            $result = $this->midtransService->createSnapToken($transaction->booking);
            
            if (!$result['success']) {
                throw new \Exception($result['message'] ?? 'Failed to create payment token');
            }

            // Update transaction with Midtrans data
            $transaction->update([
                'snap_token' => $result['snap_token'],
                'redirect_url' => $result['redirect_url'] ?? null,
                'status' => 'pending'
            ]);

            DB::commit();

            Log::info('Midtrans payment initiated', [
                'transaction_id' => $transaction->id,
                'booking_id' => $transaction->booking_id,
                'payment_method' => $transaction->payment_method
            ]);

            // Redirect to Midtrans Snap
            if ($transaction->payment_method === 'bank_transfer') {
                return redirect($result['redirect_url'])->with('success', 'Mengarahkan ke halaman pembayaran bank transfer...');
            } else {
                return redirect($result['redirect_url'])->with('success', 'Mengarahkan ke halaman pembayaran e-wallet...');
            }

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Process cash payment (no Midtrans needed)
     */
    private function processCashPayment(Transaction $transaction)
    {
        try {
            // For cash payment, mark as confirmed and wait for actual payment at location
            $transaction->update([
                'status' => 'confirmed', // Different from 'paid' - means method confirmed, payment pending
                'payment_method' => 'cod',
                'notes' => 'Pembayaran akan dilakukan di lokasi (Cash on Delivery)'
            ]);

            // Update booking payment status
            $transaction->booking->update([
                'payment_status' => 'confirmed' // Will be 'paid' when actually paid at location
            ]);

            DB::commit();

            // Send confirmation notification
            $this->notificationService->sendBookingStatusUpdate(
                $transaction->booking,
                'pending',
                'confirmed'
            );

            Log::info('Cash payment confirmed', [
                'transaction_id' => $transaction->id,
                'booking_id' => $transaction->booking_id
            ]);

            return redirect()->route('transactions.show', $transaction)
                ->with('success', 'Metode pembayaran cash berhasil dikonfirmasi. Silakan bayar saat tiba di lokasi.');

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Show transaction details
     */
    public function show(Transaction $transaction)
    {
        // Check if user owns this transaction
        if ($transaction->booking->user_id !== auth()->id() && !auth()->user()->hasRole(['admin', 'pegawai'])) {
            abort(403, 'Unauthorized access to transaction.');
        }

        $transaction->load(['booking.user', 'booking.service', 'booking.hairstyle']);

        return view('transactions.show', compact('transaction'));
    }

    /**
     * Handle Midtrans notification callback
     */
    public function midtransNotification(Request $request)
    {
        try {
            $notification = $request->all();
            
            // Verify the notification came from Midtrans
            if (!$this->verifyMidtransSignature($notification)) {
                Log::warning('Invalid Midtrans signature', $notification);
                return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 400);
            }

            $result = $this->midtransService->handleNotification($notification);
            
            if ($result['success']) {
                Log::info('Midtrans notification processed successfully', [
                    'order_id' => $notification['order_id'],
                    'transaction_status' => $notification['transaction_status']
                ]);
                
                return response()->json(['status' => 'success']);
            } else {
                Log::error('Failed to process Midtrans notification', [
                    'notification' => $notification,
                    'error' => $result['message']
                ]);
                
                return response()->json(['status' => 'error', 'message' => $result['message']], 400);
            }

        } catch (\Exception $e) {
            Log::error('Exception in Midtrans notification handler', [
                'notification' => $request->all(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['status' => 'error', 'message' => 'Internal server error'], 500);
        }
    }

    /**
     * Payment success page
     */
    public function paymentSuccess(Request $request)
    {
        $orderId = $request->get('order_id');
        
        if ($orderId) {
            $transaction = Transaction::where('order_id', $orderId)->first();
            
            if ($transaction && $transaction->booking->user_id === auth()->id()) {
                return view('payment.success', compact('transaction'));
            }
        }

        return redirect()->route('transactions.index')->with('success', 'Pembayaran berhasil diproses!');
    }

    /**
     * Payment error page
     */
    public function paymentError(Request $request)
    {
        $orderId = $request->get('order_id');
        
        if ($orderId) {
            $transaction = Transaction::where('order_id', $orderId)->first();
            
            if ($transaction && $transaction->booking->user_id === auth()->id()) {
                return view('payment.error', compact('transaction'));
            }
        }

        return redirect()->route('transactions.index')->with('error', 'Pembayaran gagal diproses.');
    }

    /**
     * Payment pending page
     */
    public function paymentPending(Request $request)
    {
        $orderId = $request->get('order_id');
        
        if ($orderId) {
            $transaction = Transaction::where('order_id', $orderId)->first();
            
            if ($transaction && $transaction->booking->user_id === auth()->id()) {
                return view('payment.pending', compact('transaction'));
            }
        }

        return redirect()->route('transactions.index')->with('info', 'Pembayaran sedang diproses.');
    }

    /**
     * Admin: Mark cash payment as completed
     */
    public function markCashPaid(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        if ($transaction->payment_method !== 'cod' || $transaction->status === 'paid') {
            return back()->with('error', 'Invalid transaction for cash payment completion.');
        }

        try {
            DB::beginTransaction();

            $transaction->update([
                'status' => 'paid',
                'paid_at' => now(),
                'notes' => ($transaction->notes ?? '') . ' - Dibayar tunai pada: ' . now()->format('d/m/Y H:i')
            ]);

            $transaction->booking->update([
                'payment_status' => 'paid'
            ]);

            DB::commit();

            // Send payment confirmation
            $this->notificationService->sendPaymentConfirmation($transaction);

            Log::info('Cash payment marked as paid', [
                'transaction_id' => $transaction->id,
                'marked_by' => auth()->id()
            ]);

            return back()->with('success', 'Pembayaran cash berhasil dikonfirmasi.');

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Error marking cash payment as paid', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Gagal mengkonfirmasi pembayaran cash.');
        }
    }

    /**
     * Verify Midtrans signature
     */
    private function verifyMidtransSignature(array $notification): bool
    {
        $orderId = $notification['order_id'] ?? '';
        $statusCode = $notification['status_code'] ?? '';
        $grossAmount = $notification['gross_amount'] ?? '';
        $serverKey = config('midtrans.server_key');
        $signatureKey = $notification['signature_key'] ?? '';

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return hash_equals($expectedSignature, $signatureKey);
    }
}