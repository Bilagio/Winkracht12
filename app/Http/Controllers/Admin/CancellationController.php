<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cancellation;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\CancellationReviewedNotification;

class CancellationController extends Controller
{
    /**
     * Display a listing of pending cancellation requests.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cancellations = Cancellation::with(['reservation.user', 'reservation.lessonPackage'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.cancellations.index', compact('cancellations'));
    }

    /**
     * Show a specific cancellation request.
     *
     * @param Cancellation $cancellation
     * @return \Illuminate\View\View
     */
    public function show(Cancellation $cancellation)
    {
        $cancellation->load(['reservation.user', 'reservation.lessonPackage', 'reservation.location']);
        
        return view('admin.cancellations.show', compact('cancellation'));
    }

    /**
     * Update the status of a cancellation request.
     *
     * @param Request $request
     * @param Cancellation $cancellation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Cancellation $cancellation)
    {
        // Validate the request
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_comment' => 'nullable|string|max:500',
        ]);

        // Only allow updating if status is pending
        if ($cancellation->status !== 'pending') {
            return redirect()->route('admin.cancellations.show', $cancellation)
                ->with('error', 'This cancellation request has already been reviewed.');
        }

        // Update the cancellation
        $cancellation->status = $validated['status'];
        $cancellation->admin_comment = $validated['admin_comment'];
        $cancellation->reviewed_by = Auth::id();
        $cancellation->reviewed_at = now();
        $cancellation->save();

        // Notify the customer
        $reservation = $cancellation->reservation;
        $user = $reservation->user;
        $user->notify(new CancellationReviewedNotification($cancellation));

        // If approved, update reservation status to pending-reschedule
        if ($cancellation->status === 'approved') {
            $reservation->status = 'pending-reschedule';
            $reservation->save();
        }

        return redirect()->route('admin.cancellations.index')
            ->with('success', 'Cancellation request has been ' . $validated['status'] . '.');
    }
}
