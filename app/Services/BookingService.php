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

        Log::info('Time slot availability check', [
            'requested_datetime' => $dateTime->format('Y-m-d H:i:s'),
            'duration_minutes' => $durationMinutes,
            'end_time' => $endTime->format('Y-m-d H:i:s'),
            'is_available' => !$conflictingBookings,
            'conflicting_bookings_exist' => $conflictingBookings
        ]);

        return ! $conflictingBookings;
    }

    /**
     * Get detailed slot availability information including conflicting bookings
     */
    public function getSlotAvailabilityDetails(Carbon $dateTime, int $durationMinutes): array
    {
        $endTime = $dateTime->copy()->addMinutes($durationMinutes);
        
        $conflictingBookings = Booking::with(['service', 'user'])
            ->where(function ($query) use ($dateTime, $endTime) {
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
            ->get();

        $isAvailable = $conflictingBookings->isEmpty();

        $result = [
            'is_available' => $isAvailable,
            'requested_slot' => [
                'start' => $dateTime->format('Y-m-d H:i:s'),
                'end' => $endTime->format('Y-m-d H:i:s'),
                'duration' => $durationMinutes
            ],
            'conflicting_bookings' => $conflictingBookings->map(function ($booking) {
                $bookingEndTime = Carbon::parse($booking->date_time)->addMinutes($booking->service->duration);
                return [
                    'id' => $booking->id,
                    'customer_name' => $booking->name,
                    'service_name' => $booking->service->name,
                    'start_time' => Carbon::parse($booking->date_time)->format('H:i'),
                    'end_time' => $bookingEndTime->format('H:i'),
                    'status' => $booking->status
                ];
            })->toArray()
        ];

        Log::info('Detailed slot availability check', $result);

        return $result;
    }

    /**
     * Check if booking can be made within 24 hours advance requirement
     */
    public function validateAdvanceBookingTime(Carbon $dateTime): array
    {
        $now = Carbon::now();
        $hoursInAdvance = $now->diffInHours($dateTime, false);
        
        $result = [
            'is_valid' => true,
            'errors' => [],
            'warnings' => [],
            'hours_in_advance' => $hoursInAdvance
        ];

        // Check if booking is at least 24 hours in advance
        // if ($hoursInAdvance < 24 && $dateTime->isFuture()) {
        //     $result['is_valid'] = false;
        //     $result['errors'][] = 'Pemesanan harus dilakukan minimal 24 jam sebelumnya.';
            
        //     Log::warning('Booking attempt with insufficient advance notice', [
        //         'requested_datetime' => $dateTime->format('Y-m-d H:i:s'),
        //         'hours_in_advance' => $hoursInAdvance,
        //         'minimum_required' => 24
        //     ]);
        // }

        return $result;
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
     * Get multiple available time slots for the next few days
     */
    public function getAlternativeSlots(Carbon $requestedTime, int $durationMinutes, int $maxSlots = 5): array
    {
        $current = $requestedTime->copy();
        $slots = [];
        $maxAttempts = 96; // Check up to 4 days ahead
        $attempt = 0;

        while ($attempt < $maxAttempts && count($slots) < $maxSlots) {
            if ($this->isWithinBusinessHours($current) &&
                $this->isBusinessDay($current) &&
                $this->isTimeSlotAvailable($current, $durationMinutes)) {
                
                // Only include slots that are at least 24 hours in advance
                if ($current->diffInHours(Carbon::now(), false) >= 24) {
                    $slots[] = [
                        'datetime' => $current->copy(),
                        'formatted_date' => $current->format('d/m/Y'),
                        'formatted_time' => $current->format('H:i'),
                        'formatted_full' => $current->format('d/m/Y H:i'),
                        'day_name' => $current->locale('id')->dayName
                    ];
                }
            }

            $current->addMinutes(30); // 30-minute intervals
            $attempt++;
        }

        Log::info('Alternative slots search', [
            'requested_time' => $requestedTime->format('Y-m-d H:i:s'),
            'duration_minutes' => $durationMinutes,
            'slots_found' => count($slots),
            'max_attempts' => $attempt
        ]);

        return $slots;
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
     * Check if time is within business hours (11:00 AM - 10:00 PM)
     */
    public function isWithinBusinessHours(Carbon $dateTime): bool
    {
        $hour = $dateTime->hour;
        $minute = $dateTime->minute;
        
        // Business hours: 11:00 - 22:00 (10 PM)
        $openTime = 11; // 11:00 (11 AM)
        $closeTime = 22; // 22:00 (10 PM)
        
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
     * Check if date is a business day (Open every day, no holidays)
     */
    public function isBusinessDay(Carbon $date): bool
    {
        $dayOfWeek = $date->dayOfWeek;
        $dayName = $date->format('l');
        $isBusinessDay = true; // Open every day, no holidays
        
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

        // Check 24 hours advance requirement
        $advanceValidation = $this->validateAdvanceBookingTime($dateTime);
        if (!$advanceValidation['is_valid']) {
            $result['is_valid'] = false;
            $result['errors'] = array_merge($result['errors'], $advanceValidation['errors']);
        }

        // Check business day (No longer needed as we are open every day)
        // Barbershop is now open every day, no holidays

        // Check business hours (11:00 - 22:00)
        if (!$this->isWithinBusinessHours($dateTime)) {
            $result['is_valid'] = false;
            
            $hour = $dateTime->hour;
            if ($hour < 11) {
                $result['errors'][] = 'Barbershop belum buka. Jam operasional: 11:00 - 22:00';
                $result['suggestions'][] = 'Silakan pilih waktu mulai jam 11:00';
            } elseif ($hour >= 22) {
                $result['errors'][] = 'Barbershop sudah tutup. Jam operasional: 11:00 - 22:00';
                $result['suggestions'][] = 'Silakan pilih waktu sebelum jam 22:00';
            }
            
            Log::warning('Booking attempt outside business hours', [
                'requested_time' => $dateTime->format('H:i'),
                'requested_hour' => $hour,
                'business_hours' => '11:00 - 22:00'
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
            
            throw new \Exception('Layanan akan berakhir setelah jam tutup (22:00). Silakan pilih waktu yang lebih awal.', 422);
        }

        // Validasi ketersediaan slot waktu dengan detail
        $slotDetails = $this->getSlotAvailabilityDetails($dateTime, $service->duration);
        
        if (!$slotDetails['is_available']) {
            $conflictInfo = '';
            if (!empty($slotDetails['conflicting_bookings'])) {
                $conflicts = collect($slotDetails['conflicting_bookings']);
                $conflictInfo = 'Bertabrakan dengan booking: ';
                $conflictInfo .= $conflicts->map(function ($booking) {
                    return "{$booking['service_name']} ({$booking['start_time']}-{$booking['end_time']})";
                })->join(', ');
            }
            
            try {
                $nextAvailable = $this->findNextAvailableSlot($dateTime, $service->duration);
                $nextSlotText = $nextAvailable->format('d/m/Y H:i');
            } catch (\Exception $e) {
                $nextSlotText = 'tidak ada slot tersedia dalam 2 hari ke depan';
            }
            
            Log::warning('Time slot not available - detailed', [
                'requested_time' => $dateTime->format('Y-m-d H:i:s'),
                'conflicting_bookings' => $slotDetails['conflicting_bookings'],
                'next_available' => $nextSlotText
            ]);
            
            $errorMessage = "Slot waktu tidak tersedia. {$conflictInfo} Slot terdekat: {$nextSlotText}";
            throw new \Exception($errorMessage, 409); // 409 = Conflict
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
            'in_progress' => ['completed', 'cancelled'],
            'completed' => [],
            'cancelled' => [],
        ];

        if (! in_array($status, $validTransitions[$booking->status] ?? [])) {
            throw new \Exception("Invalid status transition from {$booking->status} to {$status}");
        }

        // Start database transaction
        DB::beginTransaction();

        try {
            // Update booking status
            $updated = $booking->update(['status' => $status]);

            // If booking is being cancelled, also cancel the related transaction
            if ($status === 'cancelled') {
                $paymentController = app(\App\Http\Controllers\PaymentController::class);
                $transactionCancelled = $paymentController->cancelTransaction($booking);
                
                Log::info('Booking status updated to cancelled, transaction also cancelled', [
                    'booking_id' => $booking->id,
                    'transaction_cancelled' => $transactionCancelled
                ]);
            }

            DB::commit();
            return $updated;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update booking status', [
                'booking_id' => $booking->id,
                'status' => $status,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
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
