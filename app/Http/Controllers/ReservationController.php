<?php

namespace App\Http\Controllers;

use App\Models\LessonPackage;
use App\Models\Location;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\ReservationConfirmed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * Constructor to enforce authentication for all reservation actions
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the customer's reservations.
     */
    public function index(Request $request)
    {
        // Make sure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('message', 'Please log in to view your reservations.');
        }

        $sort = $request->query('sort', 'date');
        $direction = $request->query('direction', 'asc');
        
        // Validate sort field and direction
        if (!in_array($sort, ['date', 'time', 'status', 'created_at'])) {
            $sort = 'date';
        }
        
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }
        
        $reservations = Auth::user()->reservations()
            ->with(['lessonPackage', 'location', 'instructor'])
            ->orderBy($sort, $direction)
            ->get();
            
        return view('customer.reservations.index', compact('reservations', 'sort', 'direction'));
    }
 
    /**
     * Show the form for creating a new reservation.
     */
    public function create()
    {
        $lessonPackages = LessonPackage::where('is_active', true)->get();
        $locations = Location::where('is_active', true)->get();
        
        // Generate time slots for the dropdown (from 8 AM to 6 PM)
        $timeSlots = [];
        $startHour = 8; // 8 AM
        $endHour = 18;  // 6 PM
        
        for ($hour = $startHour; $hour < $endHour; $hour++) {
            $timeSlots[sprintf('%02d:00', $hour)] = sprintf('%d:00 %s', $hour > 12 ? $hour - 12 : $hour, $hour >= 12 ? 'PM' : 'AM');
            $timeSlots[sprintf('%02d:30', $hour)] = sprintf('%d:30 %s', $hour > 12 ? $hour - 12 : $hour, $hour >= 12 ? 'PM' : 'AM');
        }
        
        Log::info('Create reservation form loaded', [
            'user_id' => Auth::id(),
            'available_packages' => $lessonPackages->count(),
            'available_locations' => $locations->count()
        ]);
        
        return view('customer.reservations.create', compact('lessonPackages', 'locations', 'timeSlots'));
    }

    /**
     * Store a newly created reservation in storage.
     */
    public function store(Request $request)
    {
        // Debug information
        Log::info('Reservation store method called', [
            'request_data' => $request->all()
        ]);
        
        try {
            // Validate form data
            $validated = $request->validate([
                'lesson_package_id' => 'required|exists:lesson_packages,id',
                'location_id' => 'required|exists:locations,id',
                'date' => 'required|date|after:today',
                'time' => 'required|string',
                'participants' => 'required|integer|min:1|max:10',
                'notes' => 'nullable|string|max:500',
            ]);
            
            // Get the lesson package for price calculation
            $lessonPackage = LessonPackage::findOrFail($validated['lesson_package_id']);
            
            // Calculate total price
            $totalPrice = $lessonPackage->price * $validated['participants'];
            
            // Create the reservation
            $reservation = new Reservation();
            $reservation->user_id = Auth::id();
            $reservation->lesson_package_id = $validated['lesson_package_id'];
            $reservation->location_id = $validated['location_id'];
            $reservation->date = $validated['date'];
            $reservation->time = $validated['time'];
            $reservation->participants = $validated['participants'];
            $reservation->total_price = $totalPrice;
            $reservation->status = 'pending';
            $reservation->notes = $validated['notes'];
            $reservation->save();
            
            // Attempt to notify the user (but continue if it fails)
            try {
                Auth::user()->notify(new ReservationConfirmed($reservation));
            } catch (\Exception $e) {
                Log::error('Failed to send reservation confirmation notification', [
                    'error' => $e->getMessage(),
                    'reservation_id' => $reservation->id
                ]);
            }
            
            Log::info('Reservation created successfully', [
                'reservation_id' => $reservation->id,
                'user_id' => Auth::id()
            ]);
            
            return redirect()->route('customer.reservations.index')
                ->with('status', 'Your reservation has been created successfully and is awaiting confirmation.');
                
        } catch (\Exception $e) {
            Log::error('Error creating reservation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withInput()
                ->withErrors(['general' => 'There was a problem creating your reservation. Please try again.']);
        }
    }

    /**
     * Display the specified reservation.
     */
    public function show(Reservation $reservation)
    {
        // Ensure the user can only see their own reservations
        if ($reservation->user_id !== Auth::id() && !Auth::user()->isAdmin() && $reservation->instructor_id !== Auth::id()) {
            abort(403);
        }
        
        return view('customer.reservations.show', compact('reservation'));
    }

    /**
     * Show the form for editing the specified reservation.
     */
    public function edit(Reservation $reservation)
    {
        // Ensure the user can only edit their own reservations
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Don't allow editing past reservations
        if (Carbon::parse($reservation->date)->isPast()) {
            return redirect()->route('customer.reservations.index')
                ->with('error', 'You cannot modify past reservations.');
        }
        
        $lessonPackages = LessonPackage::where('is_active', true)->get();
        $locations = Location::where('is_active', true)->get();
        
        // Get available dates (next 30 days from today)
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
        
        return view('customer.reservations.edit', compact('reservation', 'lessonPackages', 'locations', 'availableDates', 'timeSlots'));
    }

    /**
     * Update the specified reservation in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        // Ensure the user can only update their own reservations
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Don't allow updating past reservations
        if (Carbon::parse($reservation->date)->isPast()) {
            return redirect()->route('customer.reservations.index')
                ->with('error', 'You cannot modify past reservations.');
        }
        
        $request->validate([
            'lesson_package_id' => 'required|exists:lesson_packages,id',
            'location_id' => 'required|exists:locations,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'participants' => 'required|integer|min:1',
        ]);

        $lessonPackage = LessonPackage::findOrFail($request->lesson_package_id);
        
        // Check if participants don't exceed max allowed
        if ($request->participants > $lessonPackage->max_participants) {
            return back()->withErrors(['participants' => 'Number of participants exceeds the maximum allowed for this lesson package.']);
        }
        
        // Calculate total price
        $totalPrice = $lessonPackage->price * $request->participants;
        
        // Check if date/time has changed
        $dateTimeChanged = $reservation->date != $request->date || $reservation->time != $request->time;
        
        // If date/time changed, we need to find an available instructor
        if ($dateTimeChanged) {
            // Find available instructor
            $instructors = User::where('role', 'instructor')->get();
            $instructorId = null;
            
            foreach ($instructors as $instructor) {
                $hasConflict = Reservation::where('instructor_id', $instructor->id)
                    ->where('date', $request->date)
                    ->where('time', $request->time)
                    ->where('status', '!=', 'cancelled')
                    ->where('id', '!=', $reservation->id) // Exclude current reservation
                    ->exists();
                    
                if (!$hasConflict) {
                    $instructorId = $instructor->id;
                    break;
                }
            }
            
            if ($instructorId) {
                $reservation->instructor_id = $instructorId;
                $reservation->status = 'confirmed';
            } else {
                $reservation->instructor_id = null;
                $reservation->status = 'pending';
            }
        }
        
        // Update the reservation
        $reservation->lesson_package_id = $request->lesson_package_id;
        $reservation->location_id = $request->location_id;
        $reservation->date = $request->date;
        $reservation->time = $request->time;
        $reservation->participants = $request->participants;
        $reservation->total_price = $totalPrice;
        $reservation->notes = $request->notes;
        $reservation->save();
        
        return redirect()->route('customer.reservations.index')
            ->with('status', 'Your reservation has been successfully updated!');
    }

    /**
     * Cancel the specified reservation.
     */
    public function destroy(Reservation $reservation)
    {
        // Ensure the user can only cancel their own reservations
        if ($reservation->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        
        // Don't allow cancelling past reservations
        if (Carbon::parse($reservation->date)->isPast()) {
            return redirect()->route('customer.reservations.index')
                ->with('error', 'You cannot cancel past reservations.');
        }
        
        // Update status to cancelled instead of deleting the record
        $reservation->status = 'cancelled';
        $reservation->save();
        
        return redirect()->route('customer.reservations.index')
            ->with('status', 'Your reservation has been cancelled.');
    }
}
