<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'instructor']);
    }
    
    public function daily(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();
        
        $lessons = Reservation::with(['user', 'lessonPackage', 'location'])
            ->where('instructor_id', Auth::id())
            ->where('date', $date->format('Y-m-d'))
            ->where('status', '!=', 'cancelled')
            ->orderBy('time')
            ->get();
            
        return view('instructor.schedule.daily', compact('lessons', 'date'));
    }
    
    public function weekly(Request $request)
    {
        $startDate = $request->week ? Carbon::parse($request->week) : Carbon::now()->startOfWeek();
        $endDate = (clone $startDate)->addDays(6);
        
        $lessons = Reservation::with(['user', 'lessonPackage', 'location'])
            ->where('instructor_id', Auth::id())
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->where('status', '!=', 'cancelled')
            ->orderBy('date')
            ->orderBy('time')
            ->get();
            
        // Group lessons by day
        $schedule = [];
        foreach ($lessons as $lesson) {
            $day = Carbon::parse($lesson->date)->format('Y-m-d');
            if (!isset($schedule[$day])) {
                $schedule[$day] = [];
            }
            $schedule[$day][] = $lesson;
        }
        
        return view('instructor.schedule.weekly', compact('schedule', 'startDate', 'endDate'));
    }
    
    public function monthly(Request $request)
    {
        $currentMonth = $request->month ? Carbon::parse($request->month) : Carbon::now();
        $startOfMonth = (clone $currentMonth)->startOfMonth();
        $endOfMonth = (clone $currentMonth)->endOfMonth();
        
        $lessons = Reservation::with(['user', 'lessonPackage'])
            ->where('instructor_id', Auth::id())
            ->whereBetween('date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
            ->where('status', '!=', 'cancelled')
            ->get();
            
        // Group lessons by date for calendar view
        $calendar = [];
        foreach ($lessons as $lesson) {
            $day = Carbon::parse($lesson->date)->format('j'); // Day of month without leading zeros
            if (!isset($calendar[$day])) {
                $calendar[$day] = [];
            }
            $calendar[$day][] = $lesson;
        }
        
        return view('instructor.schedule.monthly', compact('calendar', 'currentMonth', 'startOfMonth', 'endOfMonth'));
    }
    
    public function lessonDetails(Reservation $reservation)
    {
        // Ensure instructor only sees their own lessons
        if ($reservation->instructor_id !== Auth::id()) {
            abort(403);
        }
        
        return view('instructor.schedule.details', compact('reservation'));
    }
}
