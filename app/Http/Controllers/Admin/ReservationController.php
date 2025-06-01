<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\User;
use App\Models\LessonPackage;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Display a listing of the reservations.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Reservation::with(['user', 'instructor', 'lessonPackage', 'location']);
        
        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter by date range if provided
        if ($request->has('date_from') && $request->date_from) {
            $query->where('date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->where('date', '<=', $request->date_to);
        }
        
        $reservations = $query->orderByDesc('date')->paginate(10);
        
        return view('admin.reservations.index', compact('reservations'));
    }

    /**
     * Show the form for creating a new reservation.
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        $instructors = User::where('role', 'instructor')->orderBy('name')->get();
        $lessonPackages = LessonPackage::where('is_active', true)->orderBy('name')->get();
        $locations = Location::where('is_active', true)->orderBy('name')->get();
        
        // Pre-select customer if customer_id is provided
        $selectedCustomer = null;
        if ($request->has('customer_id')) {
            $selectedCustomer = User::where('id', $request->customer_id)
                ->where('role', 'customer')
                ->first();
        }
        
        return view('admin.reservations.create', compact('customers', 'instructors', 'lessonPackages', 'locations', 'selectedCustomer'));
    }

    /**
     * Store a newly created reservation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'instructor_id' => 'nullable|exists:users,id',
            'lesson_package_id' => 'required|exists:lesson_packages,id',
            'location_id' => 'required|exists:locations,id',
            'date' => 'required|date|after:today',
            'time' => 'required|string',
            'participants' => 'required|integer|min:1',
            'status' => 'required|in:pending,confirmed,cancelled,completed,instructor_cancelled,weather_cancelled',
            'notes' => 'nullable|string',
        ]);
        
        // Calculate total price
        $lessonPackage = LessonPackage::findOrFail($validated['lesson_package_id']);
        $validated['total_price'] = $lessonPackage->price * $validated['participants'];
        
        $reservation = Reservation::create($validated);
        
        return redirect()->route('admin.reservations.show', $reservation)
            ->with('status', 'Reservation created successfully.');
    }

    /**
     * Display the specified reservation.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\View\View
     */
    public function show(Reservation $reservation)
    {
        $reservation->load(['user', 'instructor', 'lessonPackage', 'location']);
        return view('admin.reservations.show', compact('reservation'));
    }

    /**
     * Show the form for editing the specified reservation.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\View\View
     */
    public function edit(Reservation $reservation)
    {
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        $instructors = User::where('role', 'instructor')->orderBy('name')->get();
        $lessonPackages = LessonPackage::where('is_active', true)->orderBy('name')->get();
        $locations = Location::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.reservations.edit', compact('reservation', 'customers', 'instructors', 'lessonPackages', 'locations'));
    }

    /**
     * Update the specified reservation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'instructor_id' => 'nullable|exists:users,id',
            'lesson_package_id' => 'required|exists:lesson_packages,id',
            'location_id' => 'required|exists:locations,id',
            'date' => 'required|date',
            'time' => 'required|string',
            'participants' => 'required|integer|min:1',
            'status' => 'required|in:pending,confirmed,cancelled,completed,instructor_cancelled,weather_cancelled',
            'notes' => 'nullable|string',
        ]);
        
        // Recalculate total price if package or participants changed
        if ($reservation->lesson_package_id != $validated['lesson_package_id'] || 
            $reservation->participants != $validated['participants']) {
            
            $lessonPackage = LessonPackage::findOrFail($validated['lesson_package_id']);
            $validated['total_price'] = $lessonPackage->price * $validated['participants'];
        }
        
        $reservation->update($validated);
        
        return redirect()->route('admin.reservations.show', $reservation)
            ->with('status', 'Reservation updated successfully.');
    }

    /**
     * Remove the specified reservation from storage.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        
        return redirect()->route('admin.reservations.index')
            ->with('status', 'Reservation deleted successfully.');
    }
}
