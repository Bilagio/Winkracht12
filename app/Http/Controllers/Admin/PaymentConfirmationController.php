<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationConfirmed;
use App\Mail\InstructorAssigned;

class PaymentConfirmationController extends Controller
{
    /**
     * Show form to confirm payment for a reservation
     */
    public function showConfirmationForm(Reservation $reservation)
    {
        // Only show form for pending reservations
        if ($reservation->status !== 'pending') {
            return redirect()->back()->with('error', 'This reservation is not pending confirmation.');
        }
        
        return view('admin.payments.confirm', compact('reservation'));
    }
    
    /**
     * Process payment confirmation and update reservation status
     */
    public function confirmPayment(Request $request, Reservation $reservation)
    {
        // Validate the request
        $request->validate([
            'payment_reference' => 'nullable|string|max:255',
            'payment_method' => 'required|string|in:cash,bank_transfer,credit_card,ideal',
            'payment_amount' => 'required|numeric|min:0',
        ]);
        
        // Only confirm pending reservations
        if ($reservation->status !== 'pending') {
            return redirect()->back()->with('error', 'This reservation is not pending confirmation.');
        }
        
        // Update reservation status
        $reservation->status = 'confirmed';
        $reservation->payment_reference = $request->payment_reference;
        $reservation->payment_method = $request->payment_method;
        $reservation->payment_confirmed_at = now();
        $reservation->payment_confirmed_by = auth()->id();
        $reservation->save();
        
        // Send confirmation email to customer
        Mail::to($reservation->user->email)
            ->send(new ReservationConfirmed($reservation));
            
        // Send notification to instructor if assigned
        if ($reservation->instructor) {
            Mail::to($reservation->instructor->email)
                ->send(new InstructorAssigned($reservation));
        }
        
        return redirect()->route('admin.reservations.show', $reservation)
            ->with('success', 'Payment confirmed and reservation status updated to confirmed. Notification emails have been sent.');
    }
}
