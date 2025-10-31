<?php

namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class QueueService
{
    /**
     * Generate queue number for a specific date
     */
    public function generateQueueNumber(Carbon $date): int
    {
        $dateString = $date->format('Y-m-d');

        $lastQueueNumber = Booking::whereDate('date_time', $dateString)
            ->max('queue_number') ?? 0;

        return $lastQueueNumber + 1;
    }

    /**
     * Check if daily quota is available (max 50 bookings per day)
     */
    public function isDailyQuotaAvailable(Carbon $date): array
    {
        $dateString = $date->format('Y-m-d');
        $maxDailyQuota = 50;

        $currentBookings = Booking::whereDate('date_time', $dateString)
            ->whereIn('status', ['pending', 'confirmed', 'in_progress', 'completed'])
            ->count();

        $isAvailable = $currentBookings < $maxDailyQuota;
        $remainingQuota = $maxDailyQuota - $currentBookings;

        return [
            'is_available' => $isAvailable,
            'current_bookings' => $currentBookings,
            'max_quota' => $maxDailyQuota,
            'remaining_quota' => $remainingQuota,
            'quota_exceeded' => $currentBookings >= $maxDailyQuota,
            'message' => $isAvailable 
                ? "Kuota tersedia: {$remainingQuota} dari {$maxDailyQuota} booking"
                : "Kuota harian telah terpenuhi ({$maxDailyQuota} booking). Silakan coba besok."
        ];
    }

    /**
     * Get current queue status for a date
     */
    public function getQueueStatus(Carbon $date): array
    {
        $dateString = $date->format('Y-m-d');
        $maxDailyQuota = 50;

        $totalBookings = Booking::whereDate('date_time', $dateString)
            ->whereIn('status', ['pending', 'confirmed', 'in_progress', 'completed'])
            ->count();

        $completedBookings = Booking::whereDate('date_time', $dateString)
            ->where('status', 'completed')
            ->count();

        $currentQueue = Booking::whereDate('date_time', $dateString)
            ->where('status', 'in_progress')
            ->first();

        $waitingBookings = Booking::whereDate('date_time', $dateString)
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('queue_number')
            ->get();

        $quotaInfo = $this->isDailyQuotaAvailable($date);

        return array_merge([
            'total_bookings' => $totalBookings,
            'completed_bookings' => $completedBookings,
            'current_queue' => $currentQueue?->queue_number,
            'waiting_count' => $waitingBookings->count(),
            'waiting_bookings' => $waitingBookings,
            'estimated_wait_time' => $this->calculateEstimatedWaitTime($waitingBookings),
        ], $quotaInfo);
    }

    /**
     * Calculate estimated wait time for remaining bookings
     */
    private function calculateEstimatedWaitTime($waitingBookings): int
    {
        $totalMinutes = 0;

        foreach ($waitingBookings as $booking) {
            $totalMinutes += $booking->service->duration ?? 60; // Default 60 minutes
        }

        return $totalMinutes;
    }

    /**
     * Get next customer in queue
     */
    public function getNextInQueue(Carbon $date): ?Booking
    {
        return Booking::whereDate('date_time', $date->format('Y-m-d'))
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('queue_number')
            ->first();
    }

    /**
     * Advance queue (mark current as completed and move to next)
     */
    public function advanceQueue(Carbon $date): array
    {
        DB::beginTransaction();

        try {
            // Mark current in-progress booking as completed
            $currentBooking = Booking::whereDate('date_time', $date->format('Y-m-d'))
                ->where('status', 'in_progress')
                ->first();

            if ($currentBooking) {
                $currentBooking->update(['status' => 'completed']);
            }

            // Get next in queue and mark as in-progress
            $nextBooking = $this->getNextInQueue($date);
            if ($nextBooking) {
                $nextBooking->update(['status' => 'in_progress']);
            }

            DB::commit();

            return [
                'completed_booking' => $currentBooking,
                'next_booking' => $nextBooking,
                'queue_status' => $this->getQueueStatus($date),
            ];

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Reset queue for a specific date (admin function)
     */
    public function resetQueue(Carbon $date): bool
    {
        $dateString = $date->format('Y-m-d');

        return Booking::whereDate('date_time', $dateString)
            ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
            ->update(['status' => 'pending']);
    }

    /**
     * Get queue position for a specific booking
     */
    public function getQueuePosition(Booking $booking): int
    {
        return Booking::whereDate('date_time', $booking->date_time->format('Y-m-d'))
            ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
            ->where('queue_number', '<=', $booking->queue_number)
            ->count();
    }

    /**
     * Reset daily queue numbers at midnight
     * This method should be called by a scheduled task
     */
    public function resetDailyQueue(): void
    {
        $today = Carbon::today();
        
        // Log the reset operation
        \Log::info('Daily queue reset initiated', [
            'date' => $today->format('Y-m-d'),
            'time' => $today->format('H:i:s')
        ]);

        // The queue numbers will automatically reset to 1 for the next day
        // when generateQueueNumber() is called for the new date
        // This method is mainly for logging and any additional cleanup needed
        
        return;
    }
}
