<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\InstructorIllnessCancellation;
use App\Mail\WeatherCancellation;
use Illuminate\Support\Facades\Log;

class CommunicationController extends Controller
{
    /**
     * Show the form for cancelling a lesson due to instructor illness
     */
    public function showIllnessForm(Reservation $reservation)
    {
        // Check if reservation can be cancelled
        if (in_array($reservation->status, ['cancelled', 'instructor_cancelled', 'weather_cancelled', 'completed'])) {
            return redirect()->back()->with('error', 'This lesson cannot be cancelled because it is already ' . $reservation->status);
        }
        
        return view('admin.communication.illness-form', compact('reservation'));
    }
    
    /**
     * Process the illness cancellation and send notification
     */
    public function sendIllnessNotification(Request $request, Reservation $reservation)
    {
        $request->validate([
            'additional_notes' => 'nullable|string|max:500',
        ]);
        
        try {
            // Update the reservation status
            $reservation->status = 'instructor_cancelled';
            $reservation->save();
            
            // Send email to customer
            Mail::to($reservation->user->email)
                ->send(new InstructorIllnessCancellation($reservation, $request->additional_notes));
            
            return redirect()->route('admin.reservations.show', $reservation)
                ->with('success', 'Lesson has been cancelled due to instructor illness. Customer has been notified.');
                
        } catch (\Exception $e) {
            Log::error('Failed to send illness cancellation: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to process cancellation: ' . $e->getMessage());
        }
    }
    
    /**
     * Show the form for cancelling a lesson due to weather conditions
     */
    public function showWeatherForm(Reservation $reservation)
    {
        // Check if reservation can be cancelled
        if (in_array($reservation->status, ['cancelled', 'instructor_cancelled', 'weather_cancelled', 'completed'])) {
            return redirect()->back()->with('error', 'This lesson cannot be cancelled because it is already ' . $reservation->status);
        }
        
        // In production, you would get this from a weather API
        // For demo purposes, we'll generate a random wind force that's often above 10
        $windForce = rand(8, 12);
        
        return view('admin.communication.weather-form', compact('reservation', 'windForce'));
    }
    
    /**
     * Process the weather cancellation and send notification
     */
    public function sendWeatherNotification(Request $request, Reservation $reservation)
    {
        $request->validate([
            'wind_force' => 'required|numeric|min:1',
            'additional_notes' => 'nullable|string|max:500',
        ]);
        
        try {
            // Update the reservation status
            $reservation->status = 'weather_cancelled';
            $reservation->save();
            
            // Send email to customer
            Mail::to($reservation->user->email)
                ->send(new WeatherCancellation(
                    $reservation, 
                    $request->wind_force, 
                    $request->additional_notes
                ));
            
            return redirect()->route('admin.reservations.show', $reservation)
                ->with('success', 'Lesson has been cancelled due to weather conditions. Customer has been notified.');
                
        } catch (\Exception $e) {
            Log::error('Failed to send weather cancellation: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to process cancellation: ' . $e->getMessage());
        }
    }
}
