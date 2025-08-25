<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Send booking confirmation notification
     */
    public function sendBookingConfirmation(Booking $booking): bool
    {
        try {
            // Send email notification
            $emailSent = $this->sendBookingConfirmationEmail($booking);

            // Send SMS notification
            $smsSent = $this->sendBookingConfirmationSMS($booking);

            Log::info('Booking confirmation sent', [
                'booking_id' => $booking->id,
                'email_sent' => $emailSent,
                'sms_sent' => $smsSent,
            ]);

            return $emailSent || $smsSent;

        } catch (\Exception $e) {
            Log::error('Error sending booking confirmation', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send payment confirmation notification
     */
    public function sendPaymentConfirmation(Transaction $transaction): bool
    {
        try {
            $booking = $transaction->booking;

            // Send email notification
            $emailSent = $this->sendPaymentConfirmationEmail($transaction);

            // Send SMS notification
            $smsSent = $this->sendPaymentConfirmationSMS($transaction);

            Log::info('Payment confirmation sent', [
                'transaction_id' => $transaction->id,
                'booking_id' => $booking->id,
                'email_sent' => $emailSent,
                'sms_sent' => $smsSent,
            ]);

            return $emailSent || $smsSent;

        } catch (\Exception $e) {
            Log::error('Error sending payment confirmation', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send booking reminder (1 hour before)
     */
    public function sendBookingReminder(Booking $booking): bool
    {
        try {
            // Send email reminder
            $emailSent = $this->sendBookingReminderEmail($booking);

            // Send SMS reminder
            $smsSent = $this->sendBookingReminderSMS($booking);

            Log::info('Booking reminder sent', [
                'booking_id' => $booking->id,
                'email_sent' => $emailSent,
                'sms_sent' => $smsSent,
            ]);

            return $emailSent || $smsSent;

        } catch (\Exception $e) {
            Log::error('Error sending booking reminder', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send booking status update notification
     */
    public function sendBookingStatusUpdate(Booking $booking, string $oldStatus, string $newStatus): bool
    {
        try {
            // Send email notification
            $emailSent = $this->sendStatusUpdateEmail($booking, $oldStatus, $newStatus);

            // Send SMS notification
            $smsSent = $this->sendStatusUpdateSMS($booking, $oldStatus, $newStatus);

            Log::info('Booking status update sent', [
                'booking_id' => $booking->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'email_sent' => $emailSent,
                'sms_sent' => $smsSent,
            ]);

            return $emailSent || $smsSent;

        } catch (\Exception $e) {
            Log::error('Error sending status update', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send booking confirmation email
     */
    private function sendBookingConfirmationEmail(Booking $booking): bool
    {
        try {
            Mail::send('emails.booking-confirmation', compact('booking'), function ($message) use ($booking) {
                $message->to($booking->user->email, $booking->user->name)
                    ->subject('Konfirmasi Booking - '.config('app.name'));
            });

            return true;

        } catch (\Exception $e) {
            Log::error('Error sending booking confirmation email', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send payment confirmation email
     */
    private function sendPaymentConfirmationEmail(Transaction $transaction): bool
    {
        try {
            $booking = $transaction->booking;

            Mail::send('emails.payment-confirmation', compact('transaction', 'booking'), function ($message) use ($booking) {
                $message->to($booking->user->email, $booking->user->name)
                    ->subject('Konfirmasi Pembayaran - '.config('app.name'));
            });

            return true;

        } catch (\Exception $e) {
            Log::error('Error sending payment confirmation email', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send booking reminder email
     */
    private function sendBookingReminderEmail(Booking $booking): bool
    {
        try {
            Mail::send('emails.booking-reminder', compact('booking'), function ($message) use ($booking) {
                $message->to($booking->user->email, $booking->user->name)
                    ->subject('Reminder Booking - '.config('app.name'));
            });

            return true;

        } catch (\Exception $e) {
            Log::error('Error sending booking reminder email', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send status update email
     */
    private function sendStatusUpdateEmail(Booking $booking, string $oldStatus, string $newStatus): bool
    {
        try {
            Mail::send('emails.status-update', compact('booking', 'oldStatus', 'newStatus'), function ($message) use ($booking) {
                $message->to($booking->user->email, $booking->user->name)
                    ->subject('Update Status Booking - '.config('app.name'));
            });

            return true;

        } catch (\Exception $e) {
            Log::error('Error sending status update email', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send SMS notification using third-party service
     */
    private function sendSMS(string $phoneNumber, string $message): bool
    {
        try {
            // Example using Twilio or local SMS gateway
            $smsProvider = config('notifications.sms_provider');

            if ($smsProvider === 'twilio') {
                return $this->sendTwilioSMS($phoneNumber, $message);
            } elseif ($smsProvider === 'nexmo') {
                return $this->sendNexmoSMS($phoneNumber, $message);
            } elseif ($smsProvider === 'local') {
                return $this->sendLocalSMS($phoneNumber, $message);
            }

            return false;

        } catch (\Exception $e) {
            Log::error('Error sending SMS', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send booking confirmation SMS
     */
    private function sendBookingConfirmationSMS(Booking $booking): bool
    {
        $message = "Booking dikonfirmasi!\n";
        $message .= "Layanan: {$booking->service->name}\n";
        $message .= "Tanggal: {$booking->date_time->format('d/m/Y H:i')}\n";
        $message .= "Antrian: {$booking->queue_number}\n";
        $message .= 'Total: Rp '.number_format($booking->total_price, 0, ',', '.');

        return $this->sendSMS($booking->user->no_telepon, $message);
    }

    /**
     * Send payment confirmation SMS
     */
    private function sendPaymentConfirmationSMS(Transaction $transaction): bool
    {
        $booking = $transaction->booking;

        $message = "Pembayaran berhasil!\n";
        $message .= "Order ID: {$transaction->order_id}\n";
        $message .= 'Total: Rp '.number_format($transaction->total_amount, 0, ',', '.');
        $message .= "\nBooking Anda telah dikonfirmasi.";

        return $this->sendSMS($booking->user->no_telepon, $message);
    }

    /**
     * Send booking reminder SMS
     */
    private function sendBookingReminderSMS(Booking $booking): bool
    {
        $message = "Reminder: Booking Anda 1 jam lagi!\n";
        $message .= "Layanan: {$booking->service->name}\n";
        $message .= "Waktu: {$booking->date_time->format('d/m/Y H:i')}\n";
        $message .= 'Jangan terlambat ya!';

        return $this->sendSMS($booking->user->no_telepon, $message);
    }

    /**
     * Send status update SMS
     */
    private function sendStatusUpdateSMS(Booking $booking, string $oldStatus, string $newStatus): bool
    {
        $statusMessages = [
            'confirmed' => 'dikonfirmasi',
            'in_progress' => 'sedang berlangsung',
            'completed' => 'selesai',
            'cancelled' => 'dibatalkan',
        ];

        $statusText = $statusMessages[$newStatus] ?? $newStatus;

        $message = "Status booking Anda telah {$statusText}.\n";
        $message .= "Layanan: {$booking->service->name}\n";
        $message .= "Tanggal: {$booking->date_time->format('d/m/Y H:i')}";

        return $this->sendSMS($booking->user->no_telepon, $message);
    }

    /**
     * Send SMS via local gateway (example implementation)
     */
    private function sendLocalSMS(string $phoneNumber, string $message): bool
    {
        try {
            // Example local SMS gateway integration
            $response = Http::post(config('notifications.local_sms_api'), [
                'phone' => $phoneNumber,
                'message' => $message,
                'api_key' => config('notifications.local_sms_key'),
            ]);

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('Local SMS sending failed', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send SMS via Twilio
     */
    private function sendTwilioSMS(string $phoneNumber, string $message): bool
    {
        // Implementation would go here
        return false;
    }

    /**
     * Send SMS via Nexmo
     */
    private function sendNexmoSMS(string $phoneNumber, string $message): bool
    {
        // Implementation would go here
        return false;
    }
}
