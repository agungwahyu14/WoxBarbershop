<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MidtransService
{
    protected $serverKey;
    protected $clientKey;
    protected $isProduction;
    protected $snapUrl;
    protected $apiUrl;

    public function __construct()
    {
        $this->serverKey = config('midtrans.server_key');
        $this->clientKey = config('midtrans.client_key');
        $this->isProduction = config('midtrans.is_production', false);
        
        $this->snapUrl = $this->isProduction 
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
            
        $this->apiUrl = $this->isProduction
            ? 'https://api.midtrans.com/v2'
            : 'https://api.sandbox.midtrans.com/v2';
    }

    /**
     * Create Snap token for payment
     */
    public function createSnapToken(Booking $booking): array
    {
        try {
            $orderId = 'BKG-' . $booking->id . '-' . time();
            
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $booking->total_price,
                ],
                'customer_details' => [
                    'first_name' => $booking->user->name,
                    'email' => $booking->user->email,
                    'phone' => $booking->user->no_telepon,
                ],
                'item_details' => [
                    [
                        'id' => 'service-' . $booking->service->id,
                        'price' => (int) $booking->total_price,
                        'quantity' => 1,
                        'name' => $booking->service->name,
                        'category' => 'Barbershop Service'
                    ]
                ],
                'callbacks' => [
                    'finish' => route('payment.success'),
                    'error' => route('payment.error'),
                    'pending' => route('payment.pending')
                ],
                'expiry' => [
                    'duration' => 24,
                    'unit' => 'hours'
                ],
                'custom_field1' => 'booking_id:' . $booking->id,
                'custom_field2' => 'user_id:' . $booking->user->id,
            ];

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($this->serverKey . ':')
            ])->post($this->snapUrl, $params);

            if ($response->successful()) {
                $data = $response->json();
                
                // Create transaction record
                $transaction = Transaction::create([
                    'booking_id' => $booking->id,
                    'user_id' => $booking->user->id,
                    'order_id' => $orderId,
                    'total_amount' => $booking->total_price,
                    'payment_method' => 'midtrans',
                    'status' => 'pending',
                    'snap_token' => $data['token'],
                    'redirect_url' => $data['redirect_url'] ?? null,
                ]);

                Log::info('Snap token created successfully', [
                    'booking_id' => $booking->id,
                    'order_id' => $orderId,
                    'transaction_id' => $transaction->id
                ]);

                return [
                    'success' => true,
                    'snap_token' => $data['token'],
                    'redirect_url' => $data['redirect_url'] ?? null,
                    'transaction' => $transaction
                ];
            }

            throw new \Exception('Failed to create snap token: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('Error creating snap token', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Handle payment notification from Midtrans
     */
    public function handleNotification(array $notification): array
    {
        try {
            $orderId = $notification['order_id'];
            $statusCode = $notification['status_code'];
            $grossAmount = $notification['gross_amount'];
            $signatureKey = $notification['signature_key'];

            // Verify signature
            $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);
            
            if ($signatureKey !== $expectedSignature) {
                throw new \Exception('Invalid signature');
            }

            // Find transaction
            $transaction = Transaction::where('order_id', $orderId)->first();
            
            if (!$transaction) {
                throw new \Exception('Transaction not found');
            }

            $transactionStatus = $notification['transaction_status'];
            $paymentType = $notification['payment_type'];
            
            // Update transaction based on status
            $newStatus = $this->mapMidtransStatus($transactionStatus);
            
            $transaction->update([
                'status' => $newStatus,
                'payment_type' => $paymentType,
                'transaction_id' => $notification['transaction_id'] ?? null,
                'payment_response' => json_encode($notification),
                'paid_at' => in_array($newStatus, ['paid', 'settlement']) ? now() : null,
            ]);

            // Update booking payment status
            if (in_array($newStatus, ['paid', 'settlement'])) {
                $transaction->booking->update([
                    'payment_status' => 'paid'
                ]);
                
                // Send confirmation notification
                $this->sendPaymentConfirmation($transaction);
            } elseif (in_array($newStatus, ['failed', 'cancelled', 'expired'])) {
                $transaction->booking->update([
                    'payment_status' => 'failed'
                ]);
            }

            Log::info('Payment notification processed', [
                'order_id' => $orderId,
                'status' => $newStatus,
                'payment_type' => $paymentType
            ]);

            return [
                'success' => true,
                'status' => $newStatus,
                'transaction' => $transaction
            ];

        } catch (\Exception $e) {
            Log::error('Error processing payment notification', [
                'notification' => $notification,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Check payment status
     */
    public function checkPaymentStatus(string $orderId): array
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($this->serverKey . ':')
            ])->get($this->apiUrl . '/' . $orderId . '/status');

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            throw new \Exception('Failed to check payment status');

        } catch (\Exception $e) {
            Log::error('Error checking payment status', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Map Midtrans status to our status
     */
    private function mapMidtransStatus(string $midtransStatus): string
    {
        return match($midtransStatus) {
            'settlement', 'capture' => 'paid',
            'pending' => 'pending',
            'deny', 'cancel', 'expire' => 'failed',
            'refund', 'partial_refund' => 'refunded',
            default => 'unknown'
        };
    }

    /**
     * Send payment confirmation
     */
    private function sendPaymentConfirmation(Transaction $transaction): void
    {
        // This will be implemented in NotificationService
        app(NotificationService::class)->sendPaymentConfirmation($transaction);
    }
}