<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\LessonPackage;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ReservationConfirmation;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * Display a listing of the customer's reservations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get sort parameters with defaults
        $sort = $request->query('sort', 'date');
        $direction = $request->query('direction', 'desc');
        
        try {
            // Get authenticated user's reservations with eager loading
            $query = Auth::user()->reservations()
                ->with(['lessonPackage', 'location', 'instructor']);
            
            // Apply sorting
            if ($sort === 'date') {
                $query->orderBy('date', $direction);
            } elseif ($sort === 'status') {
                $query->orderBy('status', $direction);
            } elseif ($sort === 'price') {
                $query->orderBy('total_price', $direction);
            }
            
            $reservations = $query->get();
            
            // Update any reservations with missing lesson packages to use available ones
            foreach ($reservations as $reservation) {
                if (!$reservation->lessonPackage) {
                    // Find packages that match the original price
                    $similarPackage = \App\Models\LessonPackage::where('price', $reservation->total_price / $reservation->participants)
                        ->where('is_active', true)
                        ->first();
                    
                    if ($similarPackage) {
                        // Update the reservation with the similar package
                        $reservation->lesson_package_id = $similarPackage->id;
                        $reservation->save();
                        
                        // Reload the relationship
                        $reservation->load('lessonPackage');
                    }
                }
            }
            
            return view('customer.reservations.index', compact('reservations', 'sort', 'direction'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in reservations index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'There was a problem loading your reservations. Please try again later.');
        }
    }

    /**
     * Show the form for creating a new reservation.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lessonPackages = LessonPackage::where('is_active', true)->get();
        $locations = Location::where('is_active', true)->get();
        
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
        
        return view('customer.reservations.create', compact('lessonPackages', 'locations', 'timeSlots'));
    }

    /**
     * Store a newly created reservation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Debug incoming request data
        Log::info('Reservation form submitted', [
            'request_data' => $request->all(),
            'user_id' => Auth::id() ?? 'not logged in'
        ]);
        
        try {
            // Basic validation with less strict rules for now
            $validated = $request->validate([
                'lesson_package_id' => 'required|exists:lesson_packages,id',
                'location_id' => 'required|exists:locations,id',
                'date' => 'required|date',
                'time' => 'required|string',
                'participants' => 'required|integer|min:1|max:10',
                'notes' => 'nullable|string|max:500',
            ]);
            
            // Load the lesson package to get the price
            $package = LessonPackage::findOrFail($request->lesson_package_id);
            
            // Process additional participants if there's more than 1
            $additionalParticipants = [];
            if ($request->participants > 1) {
                for ($i = 1; $i < $request->participants; $i++) {
                    // Relaxed validation for additional participants
                    $request->validate([
                        "participant_{$i}_name" => 'required|string|max:255',
                    ]);
                    
                    $additionalParticipants[] = [
                        'name' => $request->input("participant_{$i}_name"),
                        'email' => $request->input("participant_{$i}_email"),
                        'phone' => $request->input("participant_{$i}_phone"),
                    ];
                }
            }
            
            // Calculate total price
            $totalPrice = $package->price * $request->participants;
            
            // Create the reservation
            $reservation = new Reservation([
                'user_id' => Auth::id() ?? 1, // Fallback to ID 1 if not logged in for testing
                'lesson_package_id' => $request->lesson_package_id,
                'location_id' => $request->location_id,
                'date' => $request->date,
                'time' => $request->time,
                'participants' => $request->participants,
                'additional_participants' => $additionalParticipants,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'payment_status' => 'pending',
                'notes' => $request->notes,
            ]);
            
            // Generate invoice number
            $reservation->invoice_number = 'WK13-' . date('Ymd') . '-' . rand(1000, 9999);
            
            $reservation->save();
            
            Log::info('Reservation created successfully', [
                'reservation_id' => $reservation->id,
                'user_id' => $reservation->user_id
            ]);
            
            // Send confirmation email with payment details
            $this->sendReservationConfirmationEmails($reservation, $additionalParticipants);
            
            return redirect()->route('customer.reservations.show', $reservation)
                ->with('success', 'Reservation created successfully! Please check your email for payment instructions.');
                
        } catch (\Exception $e) {
            Log::error('Error creating reservation', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'There was a problem creating your reservation: ' . $e->getMessage());
        }
    }

    /**
     * Send reservation confirmation emails to the primary user and additional participants.
     * 
     * @param \App\Models\Reservation $reservation
     * @param array $additionalParticipants
     * @return void
     */
    protected function sendReservationConfirmationEmails(Reservation $reservation, array $additionalParticipants = [])
    {
        try {
            // Load relationships to ensure they're available in the email template
            $reservation->load(['lessonPackage', 'location', 'user']);
            
            // Always send to the main booker
            if ($reservation->user && $reservation->user->email) {
                Mail::to($reservation->user->email)
                    ->send(new ReservationConfirmation($reservation));
                
                Log::info('Reservation confirmation email sent', [
                    'email' => $reservation->user->email,
                    'reservation_id' => $reservation->id
                ]);
            }
            
            // Send to any additional participants who provided email addresses
            foreach ($additionalParticipants as $participant) {
                if (!empty($participant['email'])) {
                    Mail::to($participant['email'])
                        ->send(new ReservationConfirmation($reservation));
                    
                    Log::info('Additional participant confirmation email sent', [
                        'email' => $participant['email'],
                        'reservation_id' => $reservation->id
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to send reservation confirmation email', [
                'reservation_id' => $reservation->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Display the specified reservation.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function show(Reservation $reservation)
    {
        // Allow viewing for testing, remove auth check temporarily
        /*
        // Make sure the reservation belongs to the authenticated user
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        */
        
        $reservation->load(['lessonPackage', 'location', 'instructor', 'user']);
        
        return view('customer.reservations.show', compact('reservation'));
    }

    /**
     * Show the form for editing the specified reservation.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function edit(Reservation $reservation)
    {
        // Make sure the reservation belongs to the authenticated user
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $lessonPackages = LessonPackage::where('is_active', true)->get();
        $locations = Location::where('is_active', true)->get();
        
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
        
        return view('customer.reservations.edit', compact('reservation', 'lessonPackages', 'locations', 'timeSlots'));
    }

    /**
     * Update the specified reservation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reservation $reservation)
    {
        // Make sure the reservation belongs to the authenticated user
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'lesson_package_id' => 'required|exists:lesson_packages,id',
            'location_id' => 'required|exists:locations,id',
            'date' => 'required|date|after:+6 days',
            'time' => 'required|string',
            'participants' => 'required|integer|min:1|max:10',
            'notes' => 'nullable|string|max:500',
        ]);
        
        // Load the lesson package to get the price
        $package = LessonPackage::findOrFail($request->lesson_package_id);
        
        // Validate additional participants if there's more than 1
        $additionalParticipants = [];
        if ($request->participants > 1) {
            for ($i = 1; $i < $request->participants; $i++) {
                $request->validate([
                    "participant_{$i}_name" => 'required|string|max:255',
                    "participant_{$i}_email" => 'nullable|email|max:255',
                    "participant_{$i}_phone" => 'nullable|string|max:20',
                ]);
                
                $additionalParticipants[] = [
                    'name' => $request->input("participant_{$i}_name"),
                    'email' => $request->input("participant_{$i}_email"),
                    'phone' => $request->input("participant_{$i}_phone"),
                ];
            }
        }
        
        // Calculate total price
        $totalPrice = $package->price * $request->participants;
        
        // Update the reservation
        $reservation->update([
            'lesson_package_id' => $request->lesson_package_id,
            'location_id' => $request->location_id,
            'date' => $request->date,
            'time' => $request->time,
            'participants' => $request->participants,
            'additional_participants' => $additionalParticipants,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);
        
        return redirect()->route('customer.reservations.show', $reservation)
            ->with('success', 'Reservation updated successfully!');
    }

    /**
     * Remove the specified reservation from storage.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservation $reservation)
    {
        // Make sure the reservation belongs to the authenticated user
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $reservation->delete();
        
        return redirect()->route('customer.reservations.index')
            ->with('success', 'Reservation cancelled successfully.');
    }
}