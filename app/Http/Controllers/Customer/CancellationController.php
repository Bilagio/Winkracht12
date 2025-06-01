<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Cancellation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\CancellationRequestNotification;

class CancellationController extends Controller
{
    /**
     * Show the cancellation request form
     *
     * @param Reservation $reservation
     * @return \Illuminate\View\View
     */
    public function create(Reservation $reservation)
    {
        // Check if the reservation belongs to the authenticated user
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if reservation can be cancelled
        if (!$reservation->canBeCancelled()) {
            return redirect()->route('customer.reservations.show', $reservation)
                ->with('error', 'This reservation cannot be cancelled.');
        }

        // Check if there is already a pending cancellation request
        if ($reservation->hasPendingCancellation()) {
            return redirect()->route('customer.reservations.show', $reservation)
                ->with('error', 'A cancellation request is already pending for this reservation.');
        }

        return view('customer.cancellations.create', compact('reservation'));
    }

    /**
     * Store a new cancellation request
     *
     * @param Request $request
     * @param Reservation $reservation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Reservation $reservation)
    {
        // Check if the reservation belongs to the authenticated user
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if reservation can be cancelled
        if (!$reservation->canBeCancelled()) {
            return redirect()->route('customer.reservations.show', $reservation)
                ->with('error', 'This reservation cannot be cancelled.');
        }

        // Check if there is already a pending cancellation request
        if ($reservation->hasPendingCancellation()) {
            return redirect()->route('customer.reservations.show', $reservation)
                ->with('error', 'A cancellation request is already pending for this reservation.');
        }

        // Validate the request
        $validated = $request->validate([
            'reason' => 'required|string|min:10|max:500',
        ]);

        // Create the cancellation request
        $cancellation = Cancellation::create([
            'reservation_id' => $reservation->id,
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        // Notify administrators about the cancellation request
        // Find admin users and send notifications
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new CancellationRequestNotification($cancellation));
        }

        return redirect()->route('customer.reservations.show', $reservation)
            ->with('success', 'Cancellation request submitted. You will be notified when it is reviewed.');
    }

    /**
     * Show reschedule form after cancellation has been approved
     *
     * @param Reservation $reservation
     * @return \Illuminate\View\View
     */
    public function reschedule(Reservation $reservation)
    {
        // Check if the reservation belongs to the authenticated user
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if cancellation has been approved
        $cancellation = $reservation->cancellation;
        
        if (!$cancellation || $cancellation->status !== 'approved') {
            return redirect()->route('customer.reservations.show', $reservation)
                ->with('error', 'This reservation cannot be rescheduled yet.');
        }

        // Get available time slots for rescheduling
        $lessonPackage = $reservation->lessonPackage;
        $location = $reservation->location;
        
        // Generate time slots from 8:00 to 17:00
        $timeSlots = [];
        for ($hour = 8; $hour <= 17; $hour++) {
            $formattedHour = str_pad($hour, 2, '0', STR_PAD_LEFT);
            $timeSlots["$formattedHour:00"] = "$formattedHour:00";
            
            // Only add 30-minute slots if not the last hour
            if ($hour < 17) {
                $timeSlots["$formattedHour:30"] = "$formattedHour:30";
            }
        }

        return view('customer.cancellations.reschedule', compact('reservation', 'lessonPackage', 'location', 'timeSlots'));
    }

    /**
     * Store a rescheduled reservation
     *
     * @param Request $request
     * @param Reservation $reservation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeReschedule(Request $request, Reservation $reservation)
    {
        // Check if the reservation belongs to the authenticated user
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if cancellation has been approved
        $cancellation = $reservation->cancellation;
        
        if (!$cancellation || $cancellation->status !== 'approved') {
            return redirect()->route('customer.reservations.show', $reservation)
                ->with('error', 'This reservation cannot be rescheduled yet.');
        }

        // Validate the request
        $validated = $request->validate([
            'date' => 'required|date|after:today',
            'time' => 'required|string',
        ]);

        // Create a new reservation as a rescheduled version of the original
        $newReservation = $reservation->replicate([
            'status', 
            'created_at', 
            'updated_at'
        ]);
        
        $newReservation->date = $validated['date'];
        $newReservation->time = $validated['time'];
        $newReservation->status = 'pending';
        $newReservation->original_reservation_id = $reservation->id;
        $newReservation->save();

        // Mark the original reservation as cancelled
        $reservation->status = 'cancelled';
        $reservation->save();

        return redirect()->route('customer.reservations.show', $newReservation)
            ->with('success', 'Your lesson has been successfully rescheduled.');
    }
}
