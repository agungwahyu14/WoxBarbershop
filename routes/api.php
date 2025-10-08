<?php

use App\Http\Controllers\MidtransCallbackController;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/midtrans/callback', [MidtransCallbackController::class, 'handle']);

// Booking validation API
Route::middleware('auth')->group(function () {
    Route::post('/validate-booking-time', function (Request $request) {
        try {
            $request->validate([
                'date_time' => 'required|date',
                'service_id' => 'nullable|exists:services,id'
            ]);

            $bookingService = new BookingService();
            $dateTime = Carbon::parse($request->date_time);
            
            $validation = $bookingService->validateBusinessHours($dateTime);
            
            // Additional check for time slot availability if service_id provided
            if ($request->service_id && $validation['is_valid']) {
                $service = \App\Models\Service::find($request->service_id);
                if ($service && !$bookingService->isTimeSlotAvailable($dateTime, $service->duration)) {
                    try {
                        $nextAvailable = $bookingService->findNextAvailableSlot($dateTime, $service->duration);
                        $validation['is_valid'] = false;
                        $validation['errors'][] = 'Slot waktu tidak tersedia';
                        $validation['suggestions'][] = 'Slot terdekat: ' . $nextAvailable->format('d/m/Y H:i');
                    } catch (\Exception $e) {
                        $validation['warnings'][] = 'Tidak ada slot tersedia dalam 2 hari ke depan';
                    }
                }
            }

            return response()->json([
                'success' => true,
                'validation' => $validation,
                'business_hours' => [
                    'open' => '11:00',
                    'close' => '22:00',
                    'closed_days' => [] // Open every day, no holidays
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    });

    Route::get('/available-slots', function (Request $request) {
        try {
            $request->validate([
                'date' => 'required|date',
                'service_id' => 'required|exists:services,id'
            ]);

            $bookingService = new BookingService();
            $date = $request->date;
            $serviceId = $request->service_id;
            
            $slots = $bookingService->getAvailableTimeSlots($date, $date, $serviceId);
            
            return response()->json([
                'success' => true,
                'slots' => $slots,
                'date' => $date
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    });
});
