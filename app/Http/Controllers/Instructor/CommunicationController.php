<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\BadWeatherAlert;
use App\Notifications\InstructorIllness;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\InstructorIllnessCancellation;
use App\Mail\WeatherCancellation;
use Illuminate\Support\Facades\Log;

class CommunicationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'instructor']);
    }
    
    /**
     * Display the communication dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $instructor = Auth::user();
        $upcomingLessons = $instructor->instructorLessons()
            ->with(['user', 'lessonPackage', 'location'])
            ->where('date', '>=', now()->format('Y-m-d'))
            ->orderBy('date')
            ->orderBy('time')
            ->get();
            
        return view('instructor.communication.index', compact('upcomingLessons'));
    }
    
    /**
     * Show the form for sending an illness notification.
     *
     * @param  int  $reservationId
     * @return \Illuminate\Http\Response
     */
    public function showIllnessForm($reservationId)
    {
        $reservation = Reservation::with(['user', 'lessonPackage', 'location'])
            ->where('id', $reservationId)
            ->where('instructor_id', Auth::id())
            ->firstOrFail();
            
        // Check if reservation can be cancelled
        if (in_array($reservation->status, ['cancelled', 'instructor_cancelled', 'weather_cancelled', 'completed'])) {
            return redirect()->back()->with('error', 'This lesson cannot be cancelled because it is already ' . $reservation->status);
        }
        
        return view('instructor.communication.illness-form', compact('reservation'));
    }
    
    /**
     * Send illness notification to customer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $reservationId
     * @return \Illuminate\Http\Response
     */
    public function sendIllnessNotification(Request $request, $reservationId)
    {
        $request->validate([
            'message' => 'nullable|string|max:500',
            'additional_notes' => 'nullable|string|max:500',
        ]);
        
        $reservation = Reservation::with(['user'])
            ->where('id', $reservationId)
            ->where('instructor_id', Auth::id())
            ->firstOrFail();
            
        $instructor = Auth::user();
        $customMessage = $request->message ?? null;
        
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
     * Show the form for sending a bad weather notification.
     *
     * @param  int  $reservationId
     * @return \Illuminate\Http\Response
     */
    public function showWeatherForm($reservationId)
    {
        $reservation = Reservation::with(['user', 'lessonPackage', 'location'])
            ->where('id', $reservationId)
            ->where('instructor_id', Auth::id())
            ->firstOrFail();
            
        // Check if reservation can be cancelled
        if (in_array($reservation->status, ['cancelled', 'instructor_cancelled', 'weather_cancelled', 'completed'])) {
            return redirect()->back()->with('error', 'This lesson cannot be cancelled because it is already ' . $reservation->status);
        }
        
        // In production, you would get this from a weather API
        // For demo purposes, we'll generate a random wind force that's often above 10
        $windForce = rand(8, 12);
        
        return view('instructor.communication.weather-form', compact('reservation', 'windForce'));
    }
    
    /**
     * Send bad weather notification to customer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $reservationId
     * @return \Illuminate\Http\Response
     */
    public function sendWeatherNotification(Request $request, $reservationId)
    {
        $request->validate([
            'wind_force' => 'required|integer|min:10|max:12',
            'wind_speed' => 'nullable|string',
            'wind_direction' => 'nullable|string',
            'wave_height' => 'nullable|string',
            'additional_notes' => 'nullable|string|max:500',
        ]);
        
        $reservation = Reservation::with(['user'])
            ->where('id', $reservationId)
            ->where('instructor_id', Auth::id())
            ->firstOrFail();
            
        // Prepare weather details
        $weatherDetails = [
            'wind_speed' => $request->wind_speed,
            'wind_direction' => $request->wind_direction,
            'wave_height' => $request->wave_height,
            'additional_notes' => $request->additional_notes,
        ];
        
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
