<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InstructorController extends Controller
{
    /**
     * Display a listing of instructors
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'instructor');
        
        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $instructors = $query->orderBy('name')->paginate(10);
        
        return view('admin.instructors.index', compact('instructors'));
    }
    
    /**
     * Show the form for creating a new instructor
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.instructors.create');
    }
    
    /**
     * Store a newly created instructor
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['sometimes', 'nullable', 'string', 'min:8'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => [
                'nullable', 
                'date', 
                'before_or_equal:'.now()->subYears(18)->format('Y-m-d'),
                'after_or_equal:1950-01-01'
            ],
            'mobile' => ['nullable', 'string', 'max:15'],
            'bio' => ['nullable', 'string', 'max:500'],
            'specialties' => ['nullable', 'string', 'max:255'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:50'],
        ]);
        
        // Generate password if not provided
        if (empty($validated['password'])) {
            $validated['password'] = Str::random(12);
            $sendPassword = true;
        }
        
        // Always hash the password
        $validated['password'] = Hash::make($validated['password']);
        
        // Set role to instructor
        $validated['role'] = 'instructor';
        
        // Set default notification preferences
        $validated['notification_preferences'] = User::getDefaultNotificationPreferences();
        
        $instructor = User::create($validated);
        
        // Send password if auto-generated
        if (isset($sendPassword)) {
            // In a real app, you would send an email with the password here
            return redirect()->route('admin.instructors.index')
                ->with('status', 'Instructor created successfully. Auto-generated password: ' . $validated['password']);
        }
        
        return redirect()->route('admin.instructors.index')
            ->with('status', 'Instructor created successfully.');
    }
    
    /**
     * Display the specified instructor
     *
     * @param  \App\Models\User  $instructor
     * @return \Illuminate\View\View
     */
    public function show(User $instructor)
    {
        // Make sure we're only showing instructors
        if ($instructor->role !== 'instructor') {
            return redirect()->route('admin.instructors.index')
                ->with('error', 'The requested user is not an instructor.');
        }
        
        // Get instructor's lessons
        $lessons = $instructor->instructorLessons()
            ->with(['user', 'lessonPackage', 'location'])
            ->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->get();
        
        return view('admin.instructors.show', compact('instructor', 'lessons'));
    }
    
    /**
     * Show the form for editing the specified instructor
     *
     * @param  \App\Models\User  $instructor
     * @return \Illuminate\View\View
     */
    public function edit(User $instructor)
    {
        // Make sure we're only editing instructors
        if ($instructor->role !== 'instructor') {
            return redirect()->route('admin.instructors.index')
                ->with('error', 'The requested user is not an instructor.');
        }
        
        return view('admin.instructors.edit', compact('instructor'));
    }
    
    /**
     * Update the specified instructor
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $instructor
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $instructor)
    {
        // Make sure we're only updating instructors
        if ($instructor->role !== 'instructor') {
            return redirect()->route('admin.instructors.index')
                ->with('error', 'The requested user is not an instructor.');
        }
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($instructor->id)],
            'password' => ['sometimes', 'nullable', 'string', 'min:8'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => [
                'nullable', 
                'date', 
                'before_or_equal:'.now()->subYears(18)->format('Y-m-d'),
                'after_or_equal:1950-01-01'
            ],
            'mobile' => ['nullable', 'string', 'max:15'],
            'bio' => ['nullable', 'string', 'max:500'],
            'specialties' => ['nullable', 'string', 'max:255'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:50'],
        ]);
        
        // Only update password if provided
        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }
        
        $instructor->update($validated);
        
        return redirect()->route('admin.instructors.edit', $instructor)
            ->with('status', 'Instructor updated successfully.');
    }
    
    /**
     * Show confirmation before deleting the instructor
     * 
     * @param  \App\Models\User  $instructor
     * @return \Illuminate\View\View
     */
    public function confirmDelete(User $instructor)
    {
        // Make sure we're only deleting instructors
        if ($instructor->role !== 'instructor') {
            return redirect()->route('admin.instructors.index')
                ->with('error', 'The requested user is not an instructor.');
        }
        
        // Check if instructor has upcoming lessons
        $hasUpcomingLessons = $instructor->instructorLessons()
            ->where('date', '>=', now()->format('Y-m-d'))
            ->where('status', '!=', 'cancelled')
            ->exists();
        
        return view('admin.instructors.confirm-delete', compact('instructor', 'hasUpcomingLessons'));
    }
    
    /**
     * Remove the specified instructor
     *
     * @param  \App\Models\User  $instructor
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $instructor, Request $request)
    {
        // Make sure we're only deleting instructors
        if ($instructor->role !== 'instructor') {
            return redirect()->route('admin.instructors.index')
                ->with('error', 'The requested user is not an instructor.');
        }
        
        // Check if instructor has upcoming lessons
        $hasUpcomingLessons = $instructor->instructorLessons()
            ->where('date', '>=', now()->format('Y-m-d'))
            ->where('status', '!=', 'cancelled')
            ->exists();
            
        if ($hasUpcomingLessons && !$request->has('force_delete')) {
            return redirect()->route('admin.instructors.confirm-delete', $instructor)
                ->with('error', 'This instructor has upcoming lessons scheduled. Please reassign these lessons before deleting.');
        }
        
        $instructor->delete();
        
        return redirect()->route('admin.instructors.index')
            ->with('status', 'Instructor deleted successfully.');
    }
    
    /**
     * Show the instructor's schedule
     *
     * @param User $instructor
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function schedule(User $instructor, Request $request)
    {
        // Determine the view type (day, week, month)
        $viewType = $request->query('view', 'month');
        
        // Parse the date from the request or use today
        $date = $request->has('date') 
            ? Carbon::parse($request->query('date')) 
            : Carbon::today();
        
        // Variables for navigation and display
        $currentDate = $date->format('Y-m-d');
        $dateRangeText = '';
        $prevDate = '';
        $nextDate = '';
        
        // Initialize variables that all views need
        $lessons = collect([]);
        $lessonsByDate = collect([]);
        
        // Handle different view types
        switch ($viewType) {
            case 'day':
                // Day view logic
                $dateRangeText = $date->format('l, F j, Y');
                $prevDate = $date->copy()->subDay()->format('Y-m-d');
                $nextDate = $date->copy()->addDay()->format('Y-m-d');
                
                // Get lessons for this day
                $lessons = $instructor->instructorLessons()
                    ->with(['user', 'lessonPackage', 'location'])
                    ->whereDate('date', $date->format('Y-m-d'))
                    ->orderBy('time')
                    ->get();
                break;
                
            case 'week':
                // Week view logic
                $startOfWeek = $date->copy()->startOfWeek(Carbon::SUNDAY);
                $endOfWeek = $startOfWeek->copy()->endOfWeek(Carbon::SATURDAY);
                
                // Update the date range format to show full dates
                $dateRangeText = $startOfWeek->format('M j, Y') . ' - ' . $endOfWeek->format('M j, Y');
                $prevDate = $startOfWeek->copy()->subWeek()->format('Y-m-d');
                $nextDate = $startOfWeek->copy()->addWeek()->format('Y-m-d');
                
                // Important: Set our week boundaries for proper date calculation
                $weekStart = $startOfWeek->format('Y-m-d');
                $weekEnd = $endOfWeek->format('Y-m-d');
                
                // Get lessons for this week
                $rawLessons = $instructor->instructorLessons()
                    ->with(['user', 'lessonPackage', 'location'])
                    ->whereBetween('date', [
                        $weekStart, 
                        $weekEnd
                    ])
                    ->orderBy('date')
                    ->orderBy('time')
                    ->get();
                
                // Define time slots for the weekly view (8 AM to 9 PM)
                $timeSlots = [];
                for ($hour = 8; $hour <= 21; $hour++) {
                    $timeSlots[] = [
                        'value' => $hour,
                        'label' => $hour == 12 ? '12 PM' : ($hour > 12 ? ($hour - 12) . ' PM' : $hour . ' AM')
                    ];
                }
                
                // Prepare week days array
                $weekDays = [];
                $today = Carbon::today();
                
                // Create the weekdays array with proper dates
                for ($i = 0; $i < 7; $i++) {
                    $currentDay = $startOfWeek->copy()->addDays($i);
                    $weekDays[] = [
                        'date' => $currentDay->format('j M'),
                        'dayName' => $currentDay->format('D'),
                        'fullDate' => $currentDay->format('Y-m-d'),
                        'isToday' => $currentDay->isSameDay($today)
                    ];
                }
                
                // Format lessons for the weekly grid - FIXED DAY INDEX CALCULATION
                $weekLessons = [];
                foreach ($rawLessons as $lesson) {
                    $lessonDate = Carbon::parse($lesson->date);
                    
                    // Get the day of the week (0-6) by comparing dates directly
                    $dayIndex = -1;
                    for ($i = 0; $i < 7; $i++) {
                        $weekDay = $startOfWeek->copy()->addDays($i);
                        if ($lessonDate->isSameDay($weekDay)) {
                            $dayIndex = $i;
                            break;
                        }
                    }
                    
                    // Skip if we couldn't determine the day index
                    if ($dayIndex < 0) {
                        \Log::warning('Could not determine day index for lesson', [
                            'lesson_date' => $lesson->date,
                            'lesson_id' => $lesson->id,
                            'week_start' => $weekStart,
                            'week_end' => $weekEnd
                        ]);
                        continue;
                    }
                    
                    $lessonTime = Carbon::parse($lesson->time);
                    $hour = $lessonTime->hour;
                    
                    // Skip if outside our time range
                    if ($hour < 8 || $hour > 21) {
                        \Log::info('Skipping lesson outside time range', [
                            'id' => $lesson->id,
                            'time' => $lesson->time,
                            'hour' => $hour
                        ]);
                        continue;
                    }
                    
                    // Calculate position
                    $minutes = $lessonTime->minute;
                    $topPosition = $minutes * 0.46; // Adjusted for cell height
                    
                    // Calculate height based on duration
                    $duration = $lesson->duration ?? 60; // Default to 60 min if not specified
                    $height = min($duration * 0.46, 55); // Cap height to avoid overflow
                    
                    // Format the time range
                    $endTime = $lessonTime->copy()->addMinutes($duration);
                    
                    $weekLessons[] = [
                        'id' => $lesson->id,
                        'dayIndex' => $dayIndex,
                        'timeSlot' => $hour,
                        'topPosition' => $topPosition,
                        'height' => $height,
                        'timeRange' => $lessonTime->format('H:i') . ' - ' . $endTime->format('H:i'),
                        'customerName' => $lesson->user ? $lesson->user->name : 'No Customer',
                        'packageName' => $lesson->lessonPackage ? $lesson->lessonPackage->name : 'Lesson',
                        'status' => $lesson->status // Ensure status is properly passed
                    ];
                }
                
                // Set for the view
                $lessons = $rawLessons;
                break;
                
            default: // 'month' is the default
                // Month view logic
                $startOfMonth = $date->copy()->startOfMonth();
                $endOfMonth = $date->copy()->endOfMonth();
                
                // Get days from previous month to fill the first row and days from next month to complete the grid
                $startDay = $startOfMonth->copy()->startOfWeek(Carbon::SUNDAY);
                $endDay = $endOfMonth->copy()->endOfWeek(Carbon::SATURDAY);
                
                $dateRangeText = $date->format('F Y');
                $prevDate = $date->copy()->subMonth()->format('Y-m-d');
                $nextDate = $date->copy()->addMonth()->format('Y-m-d');
                
                // Get all lessons for this month's display (including overflow days from prev/next month)
                $monthLessons = $instructor->instructorLessons()
                    ->with(['user', 'lessonPackage', 'location'])
                    ->whereBetween('date', [
                        $startDay->format('Y-m-d'), 
                        $endDay->format('Y-m-d')
                    ])
                    ->orderBy('date')
                    ->orderBy('time')
                    ->get();
    
                // Debug the lessons to see what's retrieved
                \Log::info("Retrieved month lessons", [
                    'count' => $monthLessons->count(),
                    'first_few' => $monthLessons->take(3)->map(function($lesson) {
                        return [
                            'id' => $lesson->id,
                            'date' => $lesson->date,
                            'time' => $lesson->time,
                            'customer' => $lesson->user ? $lesson->user->name : 'No customer'
                        ];
                    })
                ]);
                
                // Group lessons by date for easy access
                // Make sure we're grouping by the date string exactly as it appears in the $dayKey format
                $lessonsByDate = $monthLessons->groupBy(function($lesson) {
                    // Ensure consistent date format for comparison
                    return Carbon::parse($lesson->date)->format('Y-m-d');
                });
                
                \Log::info('Month lessons by date', [
                    'keys' => $lessonsByDate->keys()->all(),
                    'counts' => $lessonsByDate->map->count()->all()
                ]);
                
                // Create calendar days array for the grid
                $calendarDays = [];
                
                $currentDay = $startDay->copy();
                $today = Carbon::today();
                
                $daysWithLessons = 0;
                
                while ($currentDay <= $endDay) {
                    $dayKey = $currentDay->format('Y-m-d');
                    
                    // Get lessons for this day with explicit format check
                    $dayLessons = $lessonsByDate->get($dayKey, collect([]));
                    
                    // Debug each day that should have lessons
                    if ($lessonsByDate->has($dayKey)) {
                        \Log::info("Found lessons for day {$dayKey}", [
                            'count' => $dayLessons->count(),
                            'lessons' => $dayLessons->map(function($l) {
                                return ['id' => $l->id, 'time' => $l->time];
                            })->all()
                        ]);
                        $daysWithLessons++;
                    }
                    
                    $calendarDays[] = [
                        'dayNumber' => $currentDay->format('j'),
                        'fullDate' => $dayKey,
                        'isCurrentMonth' => $currentDay->month === $startOfMonth->month,
                        'isToday' => $currentDay->isSameDay($today),
                        'lessons' => $dayLessons,
                        'hasLessons' => $dayLessons->count() > 0
                    ];
                    
                    $currentDay->addDay();
                }
                
                \Log::info("Calendar days with lessons", [
                    'days_with_lessons' => $daysWithLessons,
                    'total_days' => count($calendarDays),
                    'sample' => collect($calendarDays)->filter(fn($day) => $day['hasLessons'])->take(2)->all()
                ]);
                
                // Set lessons variable for view
                $lessons = $monthLessons;
                break;
        }

        // Return the view with appropriate data
        return view('admin.instructors.schedule', compact(
            'instructor',
            'viewType',
            'currentDate',
            'dateRangeText',
            'prevDate',
            'nextDate',
            'lessons',
            'lessonsByDate' // Make sure lessonsByDate is always passed
        ))->with(isset($weekDays) ? compact('weekDays', 'timeSlots', 'weekLessons') : [])
          ->with(isset($calendarDays) ? compact('calendarDays') : []);
    }
}
