<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CancelExpiredBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cancel-expired-bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically cancel and expire bookings based on time constraints';

    /**
     * Booking status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';

    /**
     * Transaction status constants
     */
    const TRANSACTION_CANCEL = 'cancel';
    const TRANSACTION_EXPIRE = 'expire';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('==============================================');
        $this->info('Starting Booking Status Automation Check...');
        $this->info('Current Time: ' . Carbon::now()->format('Y-m-d H:i:s'));
        $this->info('==============================================');

        $now = Carbon::now();
        $expiredThreshold = $now->copy()->subHours(24);

        $expiredCount = 0;
        $cancelledCount = 0;

        DB::beginTransaction();

        try {
            // ============================================================
            // STEP 1: Mark bookings as EXPIRED (older than 24 hours)
            // Rule: booking_time + 24 hours < now AND status in (pending, cancelled)
            // This runs FIRST to catch old records before cancel logic
            // ============================================================
            $expiredCount = $this->processExpiredBookings($expiredThreshold, $now);

            // ============================================================
            // STEP 2: Mark bookings as CANCELLED (passed but < 24 hours)
            // Rule: booking_time < now AND booking_time >= (now - 24h) AND status = pending
            // ============================================================
            $cancelledCount = $this->processCancelledBookings($expiredThreshold, $now);

            DB::commit();

            // Summary
            $this->newLine();
            $this->info('==============================================');
            $this->info('SUMMARY:');
            $this->info("  - Bookings marked as EXPIRED: {$expiredCount}");
            $this->info("  - Bookings marked as CANCELLED: {$cancelledCount}");
            $this->info('==============================================');

            Log::info('Booking status automation completed', [
                'expired_count' => $expiredCount,
                'cancelled_count' => $cancelledCount,
                'processed_at' => $now->format('Y-m-d H:i:s'),
            ]);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to process booking status automation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->error('Failed to process bookings: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }

    /**
     * Process bookings that should be marked as EXPIRED
     * Bookings older than 24 hours with pending or cancelled status
     */
    private function processExpiredBookings(Carbon $expiredThreshold, Carbon $now): int
    {
        $this->newLine();
        $this->info('[STEP 1] Processing EXPIRED bookings (older than 24 hours)...');

        $expiredBookings = Booking::whereIn('status', [self::STATUS_PENDING, self::STATUS_CANCELLED])
            ->where('date_time', '<', $expiredThreshold)
            ->with(['transactions', 'user'])
            ->get();

        if ($expiredBookings->isEmpty()) {
            $this->line('  → No bookings to mark as expired.');
            return 0;
        }

        $this->line("  → Found {$expiredBookings->count()} bookings to mark as expired.");

        $count = 0;
        foreach ($expiredBookings as $booking) {
            $previousStatus = $booking->status;
            $booking->status = self::STATUS_EXPIRED;
            $booking->save();

            // Update related transactions to expired
            $this->updateTransactionsStatus($booking, self::TRANSACTION_EXPIRE);

            $count++;

            Log::info('Booking marked as expired', [
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'customer_name' => $booking->name,
                'previous_status' => $previousStatus,
                'booking_date_time' => $booking->date_time->format('Y-m-d H:i:s'),
                'hours_since_booking' => $booking->date_time->diffInHours($now),
                'processed_at' => $now->format('Y-m-d H:i:s'),
            ]);

            $this->line("    ✓ Expired: Booking #{$booking->id} - {$booking->name} ({$booking->date_time->format('d/m/Y H:i')}) [was: {$previousStatus}]");
        }

        return $count;
    }

    /**
     * Process bookings that should be marked as CANCELLED
     * Bookings that have passed but less than 24 hours with pending status
     */
    private function processCancelledBookings(Carbon $expiredThreshold, Carbon $now): int
    {
        $this->newLine();
        $this->info('[STEP 2] Processing CANCELLED bookings (passed but < 24 hours)...');

        $cancelledBookings = Booking::where('status', self::STATUS_PENDING)
            ->where('date_time', '<', $now)
            ->where('date_time', '>=', $expiredThreshold)
            ->with(['transactions', 'user'])
            ->get();

        if ($cancelledBookings->isEmpty()) {
            $this->line('  → No bookings to mark as cancelled.');
            return 0;
        }

        $this->line("  → Found {$cancelledBookings->count()} bookings to mark as cancelled.");

        $count = 0;
        foreach ($cancelledBookings as $booking) {
            $booking->status = self::STATUS_CANCELLED;
            $booking->save();

            // Update related transactions to cancelled
            $this->updateTransactionsStatus($booking, self::TRANSACTION_CANCEL);

            $count++;

            Log::info('Booking auto-cancelled due to passed time', [
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'customer_name' => $booking->name,
                'booking_date_time' => $booking->date_time->format('Y-m-d H:i:s'),
                'hours_since_booking' => $booking->date_time->diffInHours($now),
                'processed_at' => $now->format('Y-m-d H:i:s'),
            ]);

            $this->line("    ✓ Cancelled: Booking #{$booking->id} - {$booking->name} ({$booking->date_time->format('d/m/Y H:i')})");
        }

        return $count;
    }

    /**
     * Update all transactions related to a booking
     */
    private function updateTransactionsStatus(Booking $booking, string $status): void
    {
        foreach ($booking->transactions as $transaction) {
            // Only update if transaction is not already settled/completed
            if (!in_array($transaction->transaction_status, ['settlement', 'capture'])) {
                $transaction->transaction_status = $status;
                $transaction->save();

                Log::info('Transaction status updated', [
                    'transaction_id' => $transaction->id,
                    'booking_id' => $booking->id,
                    'new_status' => $status,
                ]);
            }
        }
    }
}
