<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingService
{
    /**
     * Check if a time slot is available for booking
     */
    public function isTimeSlotAvailable(Carbon $dateTime, int $durationMinutes): bool
    {
        $endTime = $dateTime->copy()->addMinutes($durationMinutes);

        $conflictingBookings = Booking::where(function ($query) use ($dateTime, $endTime) {
            $query->where(function ($q) use ($dateTime) {
                // Check for overlapping bookings
                $q->where('date_time', '<=', $dateTime)
                    ->whereRaw('DATE_ADD(date_time, INTERVAL (SELECT duration FROM services WHERE id = bookings.service_id) MINUTE) > ?', [$dateTime]);
            })->orWhere(function ($q) use ($dateTime, $endTime) {
                $q->where('date_time', '<', $endTime)
                    ->where('date_time', '>', $dateTime);
            });
        })
            ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
            ->exists();

        return ! $conflictingBookings;
    }

    /**
     * Find the next available time slot
     */
    public function findNextAvailableSlot(Carbon $requestedTime, int $durationMinutes): Carbon
    {
        $current = $requestedTime->copy();
        $maxAttempts = 48; // Check up to 2 days ahead
        $attempt = 0;

        while ($attempt < $maxAttempts) {
            if ($this->isWithinBusinessHours($current) &&
                $this->isBusinessDay($current) &&
                $this->isTimeSlotAvailable($current, $durationMinutes)) {
                return $current;
            }

            $current->addMinutes(30); // 30-minute intervals
            $attempt++;
        }

        throw new \Exception('No available slots found in the next 2 days');
    }

    /**
     * Calculate booking price including any surcharges
     */

    /**
     * Calculate booking end time
     */
    public function calculateEndTime(Carbon $startTime, int $durationMinutes): Carbon
    {
        return $startTime->copy()->addMinutes($durationMinutes);
    }

    /**
     * Check if time is within business hours (9 AM - 9 PM)
     */
    public function isWithinBusinessHours(Carbon $dateTime): bool
    {
        return $dateTime->hour >= 9 && $dateTime->hour < 21;
    }

    /**
     * Check if date is a business day (Monday-Saturday)
     */
    public function isBusinessDay(Carbon $date): bool
    {
        return $date->dayOfWeek !== Carbon::SUNDAY;
    }

    /**
     * Create booking with business logic validation
     */
    public function createBooking(array $data): Booking
    {
        DB::beginTransaction();

        try {
            $dateTime = Carbon::parse($data['date_time']);
            $service = Service::findOrFail($data['service_id']);

            // Validasi jam operasional
            if (! $this->isWithinBusinessHours($dateTime)) {
                throw new \Exception('Booking hanya dapat dilakukan antara jam 09:00 - 21:00');
            }

            // Validasi hari kerja
            if (! $this->isBusinessDay($dateTime)) {
                throw new \Exception('Maaf, kami tutup pada hari Minggu');
            }

            // Validasi ketersediaan slot waktu
            if (! $this->isTimeSlotAvailable($dateTime, $service->duration)) {
                $nextAvailable = $this->findNextAvailableSlot($dateTime, $service->duration);
                throw new \Exception('Slot waktu tidak tersedia. Slot terdekat: '.$nextAvailable->format('d/m/Y H:i'));
            }

            // Generate nomor antrian
            $queueService = new QueueService;
            $queueNumber = $queueService->generateQueueNumber($dateTime);

            // Simpan booking
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'name' => $data['name'],
                'service_id' => $data['service_id'],
                'hairstyle_id' => $data['hairstyle_id'],
                'date_time' => $dateTime,
                'queue_number' => $queueNumber,
                'description' => $data['description'] ?? null,
                'status' => 'pending',
                'total_price' => $service->price,
            ]);

            DB::commit();

            return $booking;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Update booking status with validation
     */
    public function updateBookingStatus(Booking $booking, string $status): bool
    {
        $validTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['in_progress', 'cancelled'],
            'in_progress' => ['completed'],
            'completed' => [],
            'cancelled' => [],
        ];

        if (! in_array($status, $validTransitions[$booking->status] ?? [])) {
            throw new \Exception("Invalid status transition from {$booking->status} to {$status}");
        }

        return $booking->update(['status' => $status]);
    }

    /**
     * Get available time slots for a date range and service
     */
    public function getAvailableTimeSlots(string $startDate, string $endDate, int $serviceId): array
    {
        $service = Service::find($serviceId);
        if (! $service) {
            return [];
        }

        $availableSlots = [];
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($current->lte($end)) {
            // Only check business days
            if ($this->isBusinessDay($current)) {
                // Generate time slots for the day (every 30 minutes from 9 AM to 9 PM)
                $dayStart = $current->copy()->setTime(9, 0);
                $dayEnd = $current->copy()->setTime(21, 0);

                while ($dayStart->lt($dayEnd)) {
                    // Check if slot has enough time for the service
                    $slotEnd = $dayStart->copy()->addMinutes($service->duration);

                    if ($slotEnd->lte($dayEnd) &&
                        $this->isTimeSlotAvailable($dayStart, $service->duration)) {

                        $availableSlots[] = [
                            'date' => $dayStart->toDateString(),
                            'time' => $dayStart->format('H:i'),
                            'datetime' => $dayStart->format('Y-m-d H:i:s'),
                            'formatted' => $dayStart->format('d/m/Y H:i'),
                        ];
                    }

                    $dayStart->addMinutes(30);
                }
            }

            $current->addDay();
        }

        return $availableSlots;
    }
}
