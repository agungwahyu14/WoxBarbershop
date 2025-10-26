<?php

use App\Services\BookingService;
use App\Models\Service;
use App\Models\Booking;
use Carbon\Carbon;

// Test validasi jam operasional dan slot availability
echo "🧪 Testing WOX Barbershop Booking Validation\n\n";

// Test 1: Jam operasional (11:00 - 22:00)
echo "1️⃣ Testing Business Hours (11:00 - 22:00)\n";

$bookingService = new BookingService();

// Test jam sebelum buka (10:00)
$earlyTime = Carbon::tomorrow()->setTime(10, 0);
$validation = $bookingService->validateBusinessHours($earlyTime);
echo "   ❌ 10:00 AM: " . ($validation['is_valid'] ? "Valid" : "Invalid") . "\n";
if (!$validation['is_valid']) {
    echo "      Error: " . implode(', ', $validation['errors']) . "\n";
}

// Test jam buka (11:00)
$openTime = Carbon::tomorrow()->setTime(11, 0);
$validation = $bookingService->validateBusinessHours($openTime);
echo "   ✅ 11:00 AM: " . ($validation['is_valid'] ? "Valid" : "Invalid") . "\n";

// Test jam tutup (22:00)
$closeTime = Carbon::tomorrow()->setTime(22, 0);
$validation = $bookingService->validateBusinessHours($closeTime);
echo "   ❌ 10:00 PM: " . ($validation['is_valid'] ? "Valid" : "Invalid") . "\n";
if (!$validation['is_valid']) {
    echo "      Error: " . implode(', ', $validation['errors']) . "\n";
}

// Test jam setelah tutup (23:00)
$lateTime = Carbon::tomorrow()->setTime(23, 0);
$validation = $bookingService->validateBusinessHours($lateTime);
echo "   ❌ 11:00 PM: " . ($validation['is_valid'] ? "Valid" : "Invalid") . "\n";
if (!$validation['is_valid']) {
    echo "      Error: " . implode(', ', $validation['errors']) . "\n";
}

echo "\n";

// Test 2: Validasi 24 jam sebelumnya
echo "2️⃣ Testing 24 Hours Advance Requirement\n";

// Test 12 jam sebelumnya (invalid)
$tomorrowMorning = Carbon::now()->addHours(12);
$advanceValidation = $bookingService->validateAdvanceBookingTime($tomorrowMorning);
echo "   ❌ 12 hours advance: " . ($advanceValidation['is_valid'] ? "Valid" : "Invalid") . "\n";
if (!$advanceValidation['is_valid']) {
    echo "      Error: " . implode(', ', $advanceValidation['errors']) . "\n";
}

// Test 25 jam sebelumnya (valid)
$validAdvanceTime = Carbon::now()->addHours(25);
$advanceValidation = $bookingService->validateAdvanceBookingTime($validAdvanceTime);
echo "   ✅ 25 hours advance: " . ($advanceValidation['is_valid'] ? "Valid" : "Invalid") . "\n";

echo "\n";

// Test 3: Cek slot availability
echo "3️⃣ Testing Slot Availability\n";

try {
    // Test slot kosong (assume no existing bookings for this time)
    $testTime = Carbon::tomorrow()->setTime(15, 0); // 3:00 PM
    $isAvailable = $bookingService->isTimeSlotAvailable($testTime, 60);
    echo "   Slot " . $testTime->format('d/m/Y H:i') . ": " . ($isAvailable ? "Available ✅" : "Not Available ❌") . "\n";

    // Test detail slot
    $slotDetails = $bookingService->getSlotAvailabilityDetails($testTime, 60);
    echo "   Conflicting bookings: " . count($slotDetails['conflicting_bookings']) . "\n";
    
    if (!empty($slotDetails['conflicting_bookings'])) {
        foreach ($slotDetails['conflicting_bookings'] as $conflict) {
            echo "      - {$conflict['service_name']}: {$conflict['start_time']}-{$conflict['end_time']} ({$conflict['customer_name']})\n";
        }
    }

} catch (Exception $e) {
    echo "   Error testing slot availability: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 4: Alternative slots
echo "4️⃣ Testing Alternative Slots Generation\n";

try {
    $requestedTime = Carbon::tomorrow()->setTime(16, 0);
    $alternativeSlots = $bookingService->getAlternativeSlots($requestedTime, 60, 3);
    
    echo "   Found " . count($alternativeSlots) . " alternative slots:\n";
    foreach ($alternativeSlots as $slot) {
        echo "      - {$slot['day_name']}, {$slot['formatted_full']}\n";
    }
    
} catch (Exception $e) {
    echo "   Error generating alternative slots: " . $e->getMessage() . "\n";
}

echo "\n";

echo "🎉 Validation Testing Complete!\n";
echo "\nValidation Rules Summary:\n";
echo "✅ Business Hours: 11:00 - 22:00 (11 AM - 10 PM)\n";
echo "✅ Advance Booking: Minimum 24 hours\n";
echo "✅ Slot Conflict Detection: Active\n";
echo "✅ Alternative Slots: Available when conflict occurs\n";
echo "✅ Open Every Day: No closed days\n";