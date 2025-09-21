<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $hour = $dateTime->hour;
        $minute = $dateTime->minute;
        
        // Business hours: 09:00 - 21:00 (9 PM)
        $openTime = 9; // 09:00
        $closeTime = 21; // 21:00 (9 PM)
        
        // Check if current time is within business hours
        $isWithinHours = $hour >= $openTime && $hour < $closeTime;
        
        Log::info('Business hours check', [
            'datetime' => $dateTime->format('Y-m-d H:i:s'),
            'hour' => $hour,
            'minute' => $minute,
            'open_time' => $openTime,
            'close_time' => $closeTime,
            'is_within_hours' => $isWithinHours
        ]);
        
        return $isWithinHours;
    }

    /**
     * Check if date is a business day (Monday-Saturday)
     */
    public function isBusinessDay(Carbon $date): bool
    {
        $dayOfWeek = $date->dayOfWeek;
        $dayName = $date->format('l');
        $isBusinessDay = $dayOfWeek !== Carbon::SUNDAY;
        
        Log::info('Business day check', [
            'date' => $date->format('Y-m-d'),
            'day_of_week' => $dayOfWeek,
            'day_name' => $dayName,
            'is_business_day' => $isBusinessDay
        ]);
        
        return $isBusinessDay;
    }

    /**
     * Get detailed business hours validation result
     */
    public function validateBusinessHours(Carbon $dateTime): array
    {
        $result = [
            'is_valid' => true,
            'errors' => [],
            'warnings' => [],
            'suggestions' => []
        ];

        // Check if booking time is in the past
        if ($dateTime->isPast()) {
            $result['is_valid'] = false;
            $result['errors'][] = 'Tidak dapat membuat booking di waktu yang sudah berlalu';
            
            Log::warning('Booking attempt in the past', [
                'requested_time' => $dateTime->format('Y-m-d H:i:s'),
                'current_time' => now()->format('Y-m-d H:i:s')
            ]);
            
            return $result;
        }

        // Check business day
        if (!$this->isBusinessDay($dateTime)) {
            $result['is_valid'] = false;
            $result['errors'][] = 'Maaf, barbershop tutup pada hari Minggu';
            $result['suggestions'][] = 'Silakan pilih hari Senin - Sabtu';
            
            // Suggest next business day
            $nextBusinessDay = $dateTime->copy();
            while (!$this->isBusinessDay($nextBusinessDay)) {
                $nextBusinessDay->addDay();
            }
            $result['suggestions'][] = 'Hari kerja berikutnya: ' . $nextBusinessDay->format('l, d F Y');
            
            Log::warning('Booking attempt on non-business day', [
                'requested_date' => $dateTime->format('Y-m-d'),
                'day_name' => $dateTime->format('l'),
                'next_business_day' => $nextBusinessDay->format('Y-m-d')
            ]);
        }

        // Check business hours
        if (!$this->isWithinBusinessHours($dateTime)) {
            $result['is_valid'] = false;
            
            $hour = $dateTime->hour;
            if ($hour < 9) {
                $result['errors'][] = 'Barbershop belum buka. Jam operasional: 09:00 - 21:00';
                $result['suggestions'][] = 'Silakan pilih waktu mulai jam 09:00';
            } elseif ($hour >= 21) {
                $result['errors'][] = 'Barbershop sudah tutup. Jam operasional: 09:00 - 21:00';
                $result['suggestions'][] = 'Silakan pilih waktu sebelum jam 21:00';
            }
            
            Log::warning('Booking attempt outside business hours', [
                'requested_time' => $dateTime->format('H:i'),
                'requested_hour' => $hour,
                'business_hours' => '09:00 - 21:00'
            ]);
        }

        // Check if booking is too far in advance (optional - max 30 days)
        if ($dateTime->diffInDays(now()) > 30) {
            $result['warnings'][] = 'Booking terlalu jauh di masa depan (maksimal 30 hari ke depan)';
            
            Log::info('Booking attempt too far in advance', [
                'requested_date' => $dateTime->format('Y-m-d'),
                'days_in_advance' => $dateTime->diffInDays(now())
            ]);
        }

        // Check if booking is very soon (less than 2 hours)
        if ($dateTime->diffInHours(now()) < 2 && $dateTime->isFuture()) {
            $result['warnings'][] = 'Booking dalam waktu dekat. Pastikan Anda bisa hadir tepat waktu';
            
            Log::info('Last minute booking attempt', [
                'requested_time' => $dateTime->format('Y-m-d H:i:s'),
                'hours_from_now' => $dateTime->diffInHours(now())
            ]);
        }

        return $result;
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

        Log::info('Booking creation attempt', [
            'user_id' => auth()->id(),
            'service_id' => $data['service_id'],
            'requested_datetime' => $dateTime->format('Y-m-d H:i:s'),
            'service_duration' => $service->duration,
            'payment_method' => $data['payment_method'] ?? null
        ]);

        // Comprehensive business hours validation
        $validation = $this->validateBusinessHours($dateTime);
        
        if (!$validation['is_valid']) {
            $errorMessage = implode('. ', $validation['errors']);
            
            Log::warning('Booking validation failed', [
                'user_id' => auth()->id(),
                'errors' => $validation['errors'],
                'suggestions' => $validation['suggestions']
            ]);
            
            throw new \Exception($errorMessage, 422); // 422 = Unprocessable Entity
        }

        // Check if service extends beyond business hours
        $bookingEndTime = $dateTime->copy()->addMinutes($service->duration);
        if (!$this->isWithinBusinessHours($bookingEndTime->subMinute())) {
            Log::warning('Service would extend beyond business hours', [
                'start_time' => $dateTime->format('H:i'),
                'service_duration' => $service->duration,
                'end_time' => $bookingEndTime->format('H:i')
            ]);
            
            throw new \Exception('Layanan akan berakhir setelah jam tutup (21:00). Silakan pilih waktu yang lebih awal.', 422);
        }

        // Validasi ketersediaan slot waktu
        if (! $this->isTimeSlotAvailable($dateTime, $service->duration)) {
            $nextAvailable = $this->findNextAvailableSlot($dateTime, $service->duration);
            
            Log::warning('Time slot not available', [
                'requested_time' => $dateTime->format('Y-m-d H:i:s'),
                'next_available' => $nextAvailable->format('Y-m-d H:i:s')
            ]);
            
            throw new \Exception('Slot waktu tidak tersedia. Slot terdekat: '.$nextAvailable->format('d/m/Y H:i'), 409); // 409 = Conflict
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
            'payment_method' => $data['payment_method'], // ✅ ditambahkan
        ]);

        Log::info('Booking created successfully', [
            'booking_id' => $booking->id,
            'user_id' => $booking->user_id,
            'queue_number' => $queueNumber,
            'datetime' => $dateTime->format('Y-m-d H:i:s'),
            'service' => $service->name,
            'total_price' => $service->price,
            'payment_method' => $booking->payment_method
        ]);

        DB::commit();

        return $booking;

    } catch (\Exception $e) {
        DB::rollback();
        
        Log::error('Booking creation failed', [
            'user_id' => auth()->id(),
            'error' => $e->getMessage(),
            'code' => $e->getCode(),
            'data' => $data,
            'trace' => $e->getTraceAsString()
        ]);
        
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
