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
     * Get current queue status for a date
     */
    public function getQueueStatus(Carbon $date): array
    {
        $dateString = $date->format('Y-m-d');
        
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

        return [
            'total_bookings' => $totalBookings,
            'completed_bookings' => $completedBookings,
            'current_queue' => $currentQueue?->queue_number,
            'waiting_count' => $waitingBookings->count(),
            'waiting_bookings' => $waitingBookings,
            'estimated_wait_time' => $this->calculateEstimatedWaitTime($waitingBookings)
        ];
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
                'queue_status' => $this->getQueueStatus($date)
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
}