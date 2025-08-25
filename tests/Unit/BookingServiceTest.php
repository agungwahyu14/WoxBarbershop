<?php

namespace Tests\Unit;

use App\Models\Booking;
use App\Models\Hairstyle;
use App\Models\Service;
use App\Models\User;
use App\Services\BookingService;
use App\Services\QueueService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class BookingServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $bookingService;

    protected $queueService;

    protected $user;

    protected $service;

    protected $hairstyle;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        Role::create(['name' => 'pelanggan']);

        // Create test data
        $this->user = User::factory()->create();
        $this->user->assignRole('pelanggan');

        $this->service = Service::factory()->create([
            'price' => 50000,
            'duration' => 60,
        ]);

        $this->hairstyle = Hairstyle::factory()->create();

        $this->bookingService = new BookingService;
        $this->queueService = new QueueService;
    }

    public function test_can_calculate_booking_price()
    {
        $booking = Booking::factory()->make([
            'service_id' => $this->service->id,
        ]);

        $price = $this->bookingService->calculatePrice($booking);

        $this->assertEquals(50000, $price);
    }

    public function test_can_generate_queue_number()
    {
        $dateTime = Carbon::now()->addDays(1)->setHour(14);

        $queueNumber = $this->queueService->generateQueueNumber($dateTime);

        $this->assertIsInt($queueNumber);
        $this->assertGreaterThan(0, $queueNumber);
    }

    public function test_queue_number_increments_correctly()
    {
        $dateTime = Carbon::now()->addDays(1)->setHour(14);

        // Create first booking
        Booking::factory()->create([
            'date_time' => $dateTime,
            'queue_number' => 1,
        ]);

        $newQueueNumber = $this->queueService->generateQueueNumber($dateTime);

        $this->assertEquals(2, $newQueueNumber);
    }

    public function test_can_check_time_slot_availability()
    {
        $dateTime = Carbon::now()->addDays(1)->setHour(14);

        // Time slot is available initially
        $isAvailable = $this->bookingService->isTimeSlotAvailable($dateTime, $this->service->duration);
        $this->assertTrue($isAvailable);

        // Create a booking
        Booking::factory()->create([
            'date_time' => $dateTime,
            'service_id' => $this->service->id,
            'status' => 'confirmed',
        ]);

        // Time slot should now be unavailable
        $isAvailable = $this->bookingService->isTimeSlotAvailable($dateTime, $this->service->duration);
        $this->assertFalse($isAvailable);
    }

    public function test_can_find_next_available_slot()
    {
        $requestedTime = Carbon::now()->addDays(1)->setHour(14);

        // Block the requested time
        Booking::factory()->create([
            'date_time' => $requestedTime,
            'service_id' => $this->service->id,
            'status' => 'confirmed',
        ]);

        $nextAvailable = $this->bookingService->findNextAvailableSlot($requestedTime, $this->service->duration);

        $this->assertNotEquals($requestedTime->format('Y-m-d H:i:s'), $nextAvailable->format('Y-m-d H:i:s'));
        $this->assertTrue($nextAvailable->greaterThan($requestedTime));
    }

    public function test_can_validate_business_hours()
    {
        // Valid business hour
        $validTime = Carbon::now()->addDays(1)->setHour(14);
        $this->assertTrue($this->bookingService->isWithinBusinessHours($validTime));

        // Invalid business hour (too early)
        $tooEarly = Carbon::now()->addDays(1)->setHour(7);
        $this->assertFalse($this->bookingService->isWithinBusinessHours($tooEarly));

        // Invalid business hour (too late)
        $tooLate = Carbon::now()->addDays(1)->setHour(22);
        $this->assertFalse($this->bookingService->isWithinBusinessHours($tooLate));
    }

    public function test_can_validate_business_days()
    {
        // Valid business day (Monday)
        $monday = Carbon::now()->next(Carbon::MONDAY);
        $this->assertTrue($this->bookingService->isBusinessDay($monday));

        // Invalid business day (Sunday)
        $sunday = Carbon::now()->next(Carbon::SUNDAY);
        $this->assertFalse($this->bookingService->isBusinessDay($sunday));
    }

    public function test_can_calculate_booking_end_time()
    {
        $startTime = Carbon::now()->addDays(1)->setHour(14);
        $duration = 60; // minutes

        $endTime = $this->bookingService->calculateEndTime($startTime, $duration);

        $this->assertEquals($startTime->addMinutes(60)->format('Y-m-d H:i:s'), $endTime->format('Y-m-d H:i:s'));
    }
}
