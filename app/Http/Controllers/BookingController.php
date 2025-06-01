<?php

namespace App\Http\Controllers;

use App\Models\LessonPackage;
use App\Models\Location;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    /**
     * Constructor - TEMPORARILY REMOVED AUTH MIDDLEWARE
     */
    public function __construct()
    {
        // Authentication middleware temporarily disabled
    }

    /**
     * Display list of user's bookings
     */
    public function index()
    {
        // TEMPORARILY DISABLED AUTH CHECK
        $reservations = collect(); // Empty collection when not authenticated
        if (Auth::check()) {
            $reservations = Auth::user()->reservations()->orderBy('date', 'asc')->get();
        }
        return view('user.bookings', compact('reservations'));
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create()
    {
        try {
            $lessonPackages = LessonPackage::where('is_active', true)->get();
            $locations = Location::where('is_active', true)->get();
            
            // Get available dates (next 30 days)
            $availableDates = collect(range(0, 30))->map(function($day) {
                return Carbon::now()->addDays($day)->format('Y-m-d');
            });
            
            // Available time slots
            $timeSlots = [
                '09:00' => '9:00 AM',
                '11:00' => '11:00 AM',
                '13:00' => '1:00 PM',
                '15:00' => '3:00 PM',
            ];
            
            // For authenticated customers, use the customer reservation form
            if (Auth::check() && Auth::user()->isCustomer()) {
                return redirect()->route('customer.reservations.create');
            }
            
            // For other users or guests, use default booking form
            return view('booking.create', compact('lessonPackages', 'locations', 'availableDates', 'timeSlots'));
            
        } catch (\Exception $e) {
            return redirect()->route('welcome')
                ->with('error', 'Booking system is currently unavailable. Please try again later.');
        }
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request)
    {
        // Make sure user is logged in before proceeding
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('message', 'Please log in to complete your booking.');
        }
        
        $request->validate([
            'lesson_package_id' => 'required|exists:lesson_packages,id',
            'location_id' => 'required|exists:locations,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'participants' => 'required|integer|min:1',
        ]);

        try {
            $lessonPackage = LessonPackage::findOrFail($request->lesson_package_id);
            
            // Check if participants don't exceed max allowed
            if ($request->participants > $lessonPackage->max_participants) {
                return back()->withErrors(['participants' => 'Number of participants exceeds the maximum allowed for this lesson package.']);
            }
            
            // Calculate total price
            $totalPrice = $lessonPackage->price * $request->participants;
            
            // Find available instructor (simple algorithm - can be improved)
            $instructors = User::where('role', 'instructor')->get();
            $instructorId = null;
            
            foreach ($instructors as $instructor) {
                $hasConflict = Reservation::where('instructor_id', $instructor->id)
                    ->where('date', $request->date)
                    ->where('time', $request->time)
                    ->where('status', '!=', 'cancelled')
                    ->exists();
                    
                if (!$hasConflict) {
                    $instructorId = $instructor->id;
                    break;
                }
            }
            
            // If no instructor is available, set status to pending for manual assignment
            $status = $instructorId ? 'confirmed' : 'pending';
            
            // Get the authenticated user ID
            $userId = Auth::id();
            
            // Log the user ID for debugging
            Log::info('Creating reservation for user', [
                'user_id' => $userId,
                'authenticated' => Auth::check()
            ]);
            
            if (!$userId) {
                throw new \Exception('User ID is null. Please log in and try again.');
            }
            
            // Create the reservation with explicit user_id
            $reservation = new Reservation();
            $reservation->user_id = $userId; // Using the authenticated user's ID
            $reservation->instructor_id = $instructorId;
            $reservation->lesson_package_id = $request->lesson_package_id;
            $reservation->location_id = $request->location_id;
            $reservation->date = $request->date;
            $reservation->time = $request->time;
            $reservation->participants = $request->participants;
            $reservation->total_price = $totalPrice;
            $reservation->status = $status;
            $reservation->notes = $request->notes;
            $reservation->save();
            
            return redirect()->route('user.bookings')
                ->with('status', 'Your lesson has been successfully booked!');
                
        } catch (\Exception $e) {
            // If there's an error, redirect to an error page with detailed message
            Log::error('Reservation creation error', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'authenticated' => Auth::check()
            ]);
            
            return back()->with('error', 'There was an error processing your booking: ' . $e->getMessage());
        }
    }
}
