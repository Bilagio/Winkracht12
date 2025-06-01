<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AvailabilityController;
use App\Models\Reservation;

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

// Route for checking availability
Route::get('/check-availability', [AvailabilityController::class, 'checkAvailability']);

// Add availability check endpoint
Route::get('/check-availability', function (Request $request) {
    $date = $request->date;
    $time = $request->time;
    $locationId = $request->location_id;
    
    // Check if there's a reservation at this date/time/location
    $existingReservation = Reservation::where('date', $date)
        ->where('time', $time)
        ->where('location_id', $locationId)
        ->where('status', '!=', 'cancelled')
        ->where('status', '!=', 'instructor_cancelled')
        ->where('status', '!=', 'weather_cancelled')
        ->first();
    
    return [
        'available' => !$existingReservation,
        'date' => $date,
        'time' => $time,
        'location_id' => $locationId
    ];
});
