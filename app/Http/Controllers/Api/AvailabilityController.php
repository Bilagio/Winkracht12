<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    /**
     * Check if a time slot is available
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'location_id' => 'required|exists:locations,id',
        ]);

        $date = $request->date;
        $time = $request->time;
        $locationId = $request->location_id;

        // Check if there's already a reservation at this date, time, and location
        $existingReservation = Reservation::where('date', $date)
            ->where('time', $time)
            ->where('location_id', $locationId)
            ->where('status', '!=', 'cancelled')
            ->exists();

        return response()->json([
            'available' => !$existingReservation
        ]);
    }
}
