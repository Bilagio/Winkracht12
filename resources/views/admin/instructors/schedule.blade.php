@php
// Helper functions for status formatting
function getStatusClass($status) {
    switch($status) {
        case 'pending':
            return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';
        case 'confirmed':
            return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
        case 'completed':
            return 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
        case 'cancelled':
            return 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
        case 'instructor_cancelled':
            return 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400';
        case 'weather_cancelled':
            return 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400';
        default:
            return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
    }
}

function getStatusBadgeClass($status) {
    return getStatusClass($status); // Reuse the same function for consistency
}

function getStatusBorderColor($status) {
    switch($status) {
        case 'pending':
            return 'border-yellow-500';
        case 'confirmed':
            return 'border-green-500';
        case 'completed':
            return 'border-blue-500';
        case 'cancelled':
            return 'border-red-500';
        case 'instructor_cancelled':
            return 'border-orange-500';
        case 'weather_cancelled':
            return 'border-purple-500';
        default:
            return 'border-gray-500';
    }
}

function getStatusDot($status) {
    switch($status) {
        case 'pending':
            return 'bg-yellow-500';
        case 'confirmed':
            return 'bg-green-500';
        case 'completed':
            return 'bg-blue-500';
        case 'cancelled':
            return 'bg-red-500';
        case 'instructor_cancelled':
            return 'bg-orange-500';
        case 'weather_cancelled':
            return 'bg-purple-500';
        default:
            return 'bg-gray-500';
    }
}
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Instructor Schedule') }} - {{ $instructor->name }}
            </h2>
            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                Admin Portal
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-between">
                <div>
                    <a href="{{ route('admin.instructors.show', $instructor) }}" class="inline-flex items-center text-sm text-blue-500 hover:text-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Back to Instructor Details
                    </a>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('admin.reservations.create', ['instructor_id' => $instructor->id]) }}" class="inline-flex items-center text-sm bg-blue-500 hover:bg-blue-700 text-white py-2 px-3 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add Lesson
                    </a>
                </div>
            </div>

            <!-- Calendar View Controls -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                        <!-- Calendar Type Selector -->
                        <div class="inline-flex rounded-md shadow-sm">
                            <a href="{{ route('admin.instructors.schedule', [$instructor, 'view' => 'day', 'date' => $currentDate]) }}" 
                               class="px-4 py-2 text-sm font-medium rounded-l-lg {{ $viewType === 'day' ? 'bg-blue-100 text-blue-700 dark:bg-blue-600 dark:text-white' : 'bg-white text-gray-700 hover:bg-gray-100 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600' }} border border-gray-300 dark:border-gray-600">
                                Day
                            </a>
                            <a href="{{ route('admin.instructors.schedule', [$instructor, 'view' => 'week', 'date' => $currentDate]) }}"
                               class="px-4 py-2 text-sm font-medium {{ $viewType === 'week' ? 'bg-blue-100 text-blue-700 dark:bg-blue-600 dark:text-white' : 'bg-white text-gray-700 hover:bg-gray-100 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600' }} border-t border-b border-gray-300 dark:border-gray-600">
                                Week
                            </a>
                            <a href="{{ route('admin.instructors.schedule', [$instructor, 'view' => 'month', 'date' => $currentDate]) }}"
                               class="px-4 py-2 text-sm font-medium rounded-r-lg {{ $viewType === 'month' ? 'bg-blue-100 text-blue-700 dark:bg-blue-600 dark:text-white' : 'bg-white text-gray-700 hover:bg-gray-100 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600' }} border border-gray-300 dark:border-gray-600">
                                Month
                            </a>
                        </div>

                        <!-- Date Navigation -->
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('admin.instructors.schedule', [$instructor, 'view' => $viewType, 'date' => $prevDate]) }}" class="p-1 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                                </svg>
                            </a>
                            <a href="{{ route('admin.instructors.schedule', [$instructor, 'view' => $viewType]) }}" class="px-3 py-1 text-sm bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-md">
                                Today
                            </a>
                            <span class="text-sm font-medium">{{ $dateRangeText }}</span>
                            <a href="{{ route('admin.instructors.schedule', [$instructor, 'view' => $viewType, 'date' => $nextDate]) }}" class="p-1 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @if ($viewType === 'day')
                <!-- Daily Schedule View -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-4">Schedule for {{ $dateRangeText }}</h3>
                        
                        @if(count($lessons) > 0)
                            <div class="space-y-4">
                                @foreach($lessons as $lesson)
                                <div class="flex border-l-4 {{ getStatusBorderColor($lesson->status) }} bg-gray-50 dark:bg-gray-700/50 p-4 rounded">
                                    <div class="flex-shrink-0 w-32">
                                        <div class="font-bold">{{ \Carbon\Carbon::parse($lesson->time)->format('H:i') }}</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ $lesson->duration ?? 60 }} min</div>
                                    </div>
                                    <div class="flex-grow">
                                        <div class="flex justify-between">
                                            <h4 class="font-medium">{{ $lesson->user->name }}</h4>
                                            <span class="px-2 py-1 text-xs rounded-full {{ getStatusBadgeClass($lesson->status) }}">
                                                {{ ucfirst(str_replace('_', ' ', $lesson->status)) }}
                                            </span>
                                        </div>
                                        <p class="text-sm">{{ $lesson->lessonPackage->name ?? 'No package' }}</p>
                                        <p class="text-sm">{{ $lesson->location->name ?? 'No location' }}</p>
                                        <div class="mt-2 flex">
                                            <a href="{{ route('admin.reservations.edit', $lesson->id) }}" class="text-sm text-blue-500 hover:underline">Edit</a>
                                            <span class="mx-2 text-gray-500">|</span>
                                            <a href="{{ route('admin.reservations.show', $lesson->id) }}" class="text-sm text-blue-500 hover:underline">Details</a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-10">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mx-auto w-12 h-12 text-gray-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No lessons scheduled</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No lessons are scheduled for this day.</p>
                                <div class="mt-6">
                                    <a href="{{ route('admin.reservations.create', ['instructor_id' => $instructor->id, 'date' => $currentDate]) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                        Add a lesson
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            @elseif ($viewType === 'week')
                <!-- Weekly Schedule View - Improved Design -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-1 sm:p-4 overflow-x-auto">
                        <!-- Weekly Calendar Grid -->
                        <div class="min-w-[1000px]">
                            <!-- Days of the week headers -->
                            <div class="grid grid-cols-8 mb-1">
                                <!-- Time column header (empty) -->
                                <div class="border-b border-gray-200 dark:border-gray-700 py-3 bg-gray-50 dark:bg-gray-800/80"></div>
                                
                                <!-- Day columns headers -->
                                @foreach($weekDays as $day)
                                <div class="border-b border-gray-200 dark:border-gray-700 text-center p-3 font-medium text-sm 
                                    {{ $day['isToday'] ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-800 dark:text-blue-400 font-semibold' : 'bg-gray-50 dark:bg-gray-800/80' }}">
                                    <div class="text-base mb-1">{{ $day['dayName'] }}</div>
                                    <span class="text-sm opacity-90 {{ $day['isToday'] ? 'font-semibold' : 'font-normal' }}">
                                        {{ $day['date'] }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                            
                            <!-- Time slots and event grid -->
                            <div class="grid grid-cols-8">
                                <!-- Time column -->
                                @foreach($timeSlots as $slot)
                                <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 pr-3 text-right py-2 text-xs font-medium text-gray-500 dark:text-gray-400 sticky left-0">
                                    {{ $slot['label'] }}
                                </div>
                                
                                <!-- Day columns for this time slot -->
                                @foreach($weekDays as $dayIndex => $day)
                                <div class="border-b border-r border-gray-200 dark:border-gray-700 h-14 relative {{ $day['isToday'] ? 'bg-blue-50/30 dark:bg-blue-900/5' : '' }}">
                                    <!-- Half-hour marker -->
                                    <div class="border-t border-dashed border-gray-200 dark:border-gray-700 absolute w-full top-1/2 opacity-60"></div>
                                    
                                    <!-- Lessons for this time slot and day -->
                                    @foreach($weekLessons as $lesson)
                                        @if($lesson['dayIndex'] == $dayIndex && $lesson['timeSlot'] == $slot['value'])
                                        @php
                                            // Make sure we're using a unique ID for each lesson wrapper
                                            $lessonId = "lesson-" . $dayIndex . "-" . $slot['value'] . "-" . $lesson['id'];
                                            
                                            // Set the border color based on status
                                            $borderColor = '';
                                            switch($lesson['status']) {
                                                case 'confirmed': $borderColor = 'var(--status-green)'; break;
                                                case 'pending': $borderColor = 'var(--status-yellow)'; break;
                                                case 'completed': $borderColor = 'var(--status-blue)'; break;
                                                case 'cancelled': $borderColor = 'var(--status-red)'; break;
                                                case 'instructor_cancelled': $borderColor = 'var(--status-orange)'; break;
                                                case 'weather_cancelled': $borderColor = 'var(--status-purple)'; break;
                                                default: $borderColor = 'var(--status-gray)'; break;
                                            }
                                            
                                            // Create the style attributes
                                            $baseStyle = "left: 0.25rem; right: 0.25rem; top: " . $lesson['topPosition'] . "px; height: " . $lesson['height'] . "px; z-index: 10; border-left-width: 3px; border-left-color: " . $borderColor . ";";
                                            $additionalStyle = in_array($lesson['status'], ['cancelled', 'instructor_cancelled', 'weather_cancelled']) ? "text-decoration-line: line-through; opacity: 0.75;" : "";
                                        @endphp
                                        
                                        <div id="{{ $lessonId }}" 
                                             class="absolute rounded-lg border shadow-sm overflow-hidden {{ getStatusClass($lesson['status']) }} cursor-pointer hover:shadow-md transition-shadow duration-200"
                                             style="{{ $baseStyle }} {{ $additionalStyle }}"
                                             onclick="window.location.href='{{ route('admin.reservations.show', $lesson['id']) }}'">
                                            <div class="h-full p-1.5">
                                                <div class="font-semibold text-xs truncate">{{ $lesson['timeRange'] }}</div>
                                                <div class="text-xs truncate mt-0.5">{{ $lesson['customerName'] }}</div>
                                                <div class="text-xs truncate opacity-80 mt-0.5 hidden sm:block">{{ $lesson['packageName'] }}</div>
                                                
                                                <!-- Status indicator dot -->
                                                <div class="absolute top-1 right-1 h-2 w-2 rounded-full {{ getStatusDot($lesson['status']) }}"></div>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                                @endforeach
                                @endforeach
                            </div>
                            
                            <!-- Legend -->
                            <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:flex gap-3 text-xs text-gray-600 dark:text-gray-400 flex-wrap justify-center">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 rounded-full bg-green-500 mr-1"></div>
                                    <span>Confirmed</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-2 h-2 rounded-full bg-yellow-500 mr-1"></div>
                                    <span>Pending</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-2 h-2 rounded-full bg-blue-500 mr-1"></div>
                                    <span>Completed</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-2 h-2 rounded-full bg-red-500 mr-1"></div>
                                    <span>Cancelled</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-2 h-2 rounded-full bg-orange-500 mr-1"></div>
                                    <span>Instructor Cancelled</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-2 h-2 rounded-full bg-purple-500 mr-1"></div>
                                    <span>Weather Cancelled</span>
                                </div>
                            </div>
                            
                            <!-- Time scale indicator -->
                            <div class="flex justify-end mt-2">
                                <div class="text-xs text-gray-500 flex items-center">
                                    <div class="w-20 h-1 bg-gradient-to-r from-gray-300 to-gray-400 dark:from-gray-600 dark:to-gray-500 mr-2 rounded-full"></div>
                                    <span>30 minutes</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <!-- Monthly Schedule View -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <!-- Calendar Grid -->
                        <div class="calendar-container">
                            <!-- Weekday Headers -->
                            <div class="grid grid-cols-7 gap-1 mb-1">
                                @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                                    <div class="text-center py-2 bg-gray-100 dark:bg-gray-700 font-semibold rounded text-gray-700 dark:text-gray-300">
                                        {{ $day }}
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Calendar Days -->
                            <div class="grid grid-cols-7 gap-1">
                                @foreach ($calendarDays as $day)
                                <div class="min-h-[120px] p-2 border border-gray-200 dark:border-gray-700 
                                    {{ $day['isCurrentMonth'] ? 'bg-white dark:bg-gray-800' : 'bg-gray-100 dark:bg-gray-700/50 text-gray-400 dark:text-gray-500' }} 
                                    {{ $day['isToday'] ? 'ring-2 ring-blue-500 dark:ring-blue-400' : '' }}">
                                    
                                    <div class="text-right text-sm font-medium mb-1 {{ $day['isToday'] ? 'text-blue-600 dark:text-blue-400' : '' }}">
                                        {{ $day['dayNumber'] }}
                                    </div>
                                    
                                    <!-- Lessons for this day -->
                                    @if(isset($day['lessons']) && $day['lessons']->count() > 0)
                                        <div class="space-y-1">
                                            @foreach($day['lessons'] as $lesson)
                                                <a href="{{ route('admin.reservations.show', $lesson->id) }}" 
                                                   class="block text-xs p-1 rounded truncate {{ getStatusClass($lesson->status) }} hover:shadow-sm">
                                                    <div class="flex items-center">
                                                        <div class="w-2 h-2 rounded-full mr-1 bg-{{ $lesson->status == 'confirmed' ? 'green' : ($lesson->status == 'pending' ? 'yellow' : 'blue') }}-500"></div>
                                                        <span>{{ \Carbon\Carbon::parse($lesson->time)->format('H:i') }}</span>
                                                        <span class="mx-1">-</span>
                                                        <span class="truncate">{{ $lesson->user ? Str::limit($lesson->user->name, 10) : 'No name' }}</span>
                                                    </div>
                                                </a>
                                            @endforeach
                                            
                                            @if($day['lessons']->count() > 3)
                                                <a href="{{ route('admin.instructors.schedule', ['instructor' => $instructor->id, 'view' => 'day', 'date' => $day['fullDate']]) }}" 
                                                   class="block text-center text-xs p-1 bg-gray-100 dark:bg-gray-700 rounded text-blue-600 dark:text-blue-400 hover:bg-gray-200 dark:hover:bg-gray-600">
                                                    + {{ $day['lessons']->count() - 3 }} more
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Lesson Details Modal -->
            <div id="lesson-modal" class="fixed inset-0 bg-black bg-opacity-50 dark:bg-opacity-70 flex items-center justify-center z-50 hidden">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6 mx-4">
                    <div class="flex justify-between items-start mb-4">
                        <h3 id="modal-title" class="text-xl font-semibold text-gray-900 dark:text-gray-100"></h3>
                        <button id="close-modal" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div id="modal-content">
                        <!-- Lesson details will be populated here -->
                    </div>
                    
                    <div class="flex justify-end mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a id="edit-lesson-link" href="#" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded mr-2">
                            Edit Lesson
                        </a>
                        <button id="close-modal-btn" class="bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 px-4 py-2 rounded">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Debug information
            console.log('Calendar loaded');
            
            // Current date tracking
            let currentDate = new Date();
            let startOfWeek = getStartOfWeek(currentDate);
            
            // Dummy data for lessons (with realistic fields)
            const dummyLessons = [
                {
                    id: 1,
                    date: formatDate(new Date(startOfWeek.getTime() + 1 * 24 * 60 * 60 * 1000)), // Monday
                    time: "10:00",
                    duration: 120, // 2 hours
                    client: "Sarah Johnson",
                    clientEmail: "sarah@example.com",
                    phone: "+31 6 12345678",
                    location: { name: "Zandvoort Beach" },
                    lessonPackage: { name: "Private Introduction", duration: 120 },
                    participants: 1,
                    status: "confirmed",
                    notes: "First time kitesurfer, very excited to learn"
                },
                {
                    id: 2,
                    date: formatDate(new Date(startOfWeek.getTime() + 1 * 24 * 60 * 60 * 1000)), // Monday
                    time: "14:00",
                    duration: 180, // 3 hours
                    client: "David Smith",
                    clientEmail: "david@example.com",
                    phone: "+31 6 23456789",
                    location: { name: "Zandvoort Beach" },
                    lessonPackage: { name: "Group Lesson", duration: 180 },
                    participants: 3,
                    status: "confirmed",
                    notes: "Group of friends with some experience"
                },
                {
                    id: 3,
                    date: formatDate(new Date(startOfWeek.getTime() + 2 * 24 * 60 * 60 * 1000)), // Tuesday
                    time: "09:00",
                    duration: 120, // 2 hours
                    client: "Emma Wilson",
                    clientEmail: "emma@example.com",
                    phone: "+31 6 34567890",
                    location: { name: "Scheveningen" },
                    lessonPackage: { name: "Advanced Techniques", duration: 120 },
                    participants: 1,
                    status: "confirmed",
                    notes: "Wants to work on jumps and tricks"
                },
                {
                    id: 4,
                    date: formatDate(new Date(startOfWeek.getTime() + 3 * 24 * 60 * 60 * 1000)), // Wednesday
                    time: "11:00",
                    duration: 120, // 2 hours
                    client: "Michael Brown",
                    clientEmail: "michael@example.com",
                    phone: "+31 6 45678901",
                    location: { name: "Hoek van Holland" },
                    lessonPackage: { name: "Private Intermediate", duration: 120 },
                    participants: 1,
                    status: "pending",
                    notes: "Second lesson, worked on water starts last time"
                },
                {
                    id: 5,
                    date: formatDate(new Date(startOfWeek.getTime() + 3 * 24 * 60 * 60 * 1000)), // Wednesday
                    time: "15:00",
                    duration: 90, // 1.5 hours
                    client: "Jennifer Davis",
                    clientEmail: "jennifer@example.com",
                    phone: "+31 6 56789012",
                    location: { name: "Zandvoort Beach" },
                    lessonPackage: { name: "Refresher Course", duration: 90 },
                    participants: 1,
                    status: "confirmed",
                    notes: "Returning after 2 years, needs confidence building"
                },
                {
                    id: 6,
                    date: formatDate(new Date(startOfWeek.getTime() + 4 * 24 * 60 * 60 * 1000)), // Thursday
                    time: "13:00",
                    duration: 240, // 4 hours
                    client: "Robert Miller",
                    clientEmail: "robert@example.com",
                    phone: "+31 6 67890123",
                    location: { name: "Scheveningen" },
                    lessonPackage: { name: "Intensive Training", duration: 240 },
                    participants: 1,
                    status: "confirmed",
                    notes: "Preparing for competition next month"
                },
                {
                    id: 7,
                    date: formatDate(new Date(startOfWeek.getTime() + 5 * 24 * 60 * 60 * 1000)), // Friday
                    time: "10:00",
                    duration: 180, // 3 hours
                    client: "Lisa Moore",
                    clientEmail: "lisa@example.com",
                    phone: "+31 6 78901234",
                    location: { name: "Ijmuiden Beach" },
                    lessonPackage: { name: "Family Package", duration: 180 },
                    participants: 4,
                    status: "confirmed",
                    notes: "Family of 4, parents and 2 teenagers (14, 16)"
                },
                {
                    id: 8,
                    date: formatDate(new Date(startOfWeek.getTime() + 6 * 24 * 60 * 60 * 1000)), // Saturday
                    time: "09:00",
                    duration: 120, // 2 hours
                    client: "James Wilson",
                    clientEmail: "james@example.com",
                    phone: "+31 6 89012345",
                    location: { name: "Zandvoort Beach" },
                    lessonPackage: { name: "Private Beginner", duration: 120 },
                    participants: 1,
                    status: "confirmed",
                    notes: "Complete beginner, very athletic background"
                },
                {
                    id: 9,
                    date: formatDate(new Date(startOfWeek.getTime() + 6 * 24 * 60 * 60 * 1000)), // Saturday
                    time: "12:00",
                    duration: 120, // 2 hours
                    client: "Emily Taylor",
                    clientEmail: "emily@example.com",
                    phone: "+31 6 90123456",
                    location: { name: "Zandvoort Beach" },
                    lessonPackage: { name: "Private Beginner", duration: 120 },
                    participants: 1,
                    status: "cancelled",
                    notes: "Cancelled due to illness"
                },
                {
                    id: 10,
                    date: formatDate(new Date(startOfWeek.getTime() + 6 * 24 * 60 * 60 * 1000)), // Saturday
                    time: "15:00",
                    duration: 180, // 3 hours
                    client: "Thomas Anderson",
                    clientEmail: "thomas@example.com",
                    phone: "+31 6 01234567",
                    location: { name: "Scheveningen" },
                    lessonPackage: { name: "Group Lesson", duration: 180 },
                    participants: 5,
                    status: "confirmed",
                    notes: "Bachelor party group, mixed experience levels"
                }
            ];
            
            // Server-provided lessons (empty until we implement API)
            const serverLessons = @json($lessonsByDate ? $lessonsByDate->flatten() : []);
            
            // Combine dummy and server lessons
            const allLessons = [...dummyLessons, ...serverLessons];

            // Set up initial week
            updateDateDisplay();
            createWeeklyGrid();
            populateEvents(allLessons);
            
            // Set up navigation buttons
            document.getElementById('prev-week').addEventListener('click', function() {
                startOfWeek.setDate(startOfWeek.getDate() - 7);
                updateDateDisplay();
                createWeeklyGrid();
                populateEvents(allLessons);
            });
            
            document.getElementById('next-week').addEventListener('click', function() {
                startOfWeek.setDate(startOfWeek.getDate() + 7);
                updateDateDisplay();
                createWeeklyGrid();
                populateEvents(allLessons);
            });
            
            document.getElementById('today-btn').addEventListener('click', function() {
                currentDate = new Date();
                startOfWeek = getStartOfWeek(currentDate);
                updateDateDisplay();
                createWeeklyGrid();
                populateEvents(allLessons);
            });

            // Set up modal close buttons
            document.getElementById('close-modal').addEventListener('click', closeModal);
            document.getElementById('close-modal-btn').addEventListener('click', closeModal);

            // Helper Functions
            function updateDateDisplay() {
                const endOfWeek = new Date(startOfWeek);
                endOfWeek.setDate(startOfWeek.getDate() + 6);
                
                const options = { month: 'short', day: 'numeric' };
                const startStr = startOfWeek.toLocaleDateString('en-US', options);
                const endStr = endOfWeek.toLocaleDateString('en-US', options);
                const yearStr = startOfWeek.getFullYear();
                
                document.getElementById('current-date-range').textContent = `${startStr} - ${endStr}, ${yearStr}`;
                
                // Update day headers with date numbers
                const days = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
                days.forEach((day, index) => {
                    const date = new Date(startOfWeek);
                    date.setDate(date.getDate() + index);
                    const dateNum = date.getDate();
                    
                    const headerEl = document.getElementById(`header-${day}`);
                    const dateSpan = headerEl.querySelector('span');
                    dateSpan.textContent = dateNum;
                    
                    // Highlight today
                    if (isToday(date)) {
                        headerEl.classList.add('bg-blue-100', 'dark:bg-blue-900/20', 'text-blue-800', 'dark:text-blue-400');
                    } else {
                        headerEl.classList.remove('bg-blue-100', 'dark:bg-blue-900/20', 'text-blue-800', 'dark:text-blue-400');
                    }
                });
            }
            
            function createWeeklyGrid() {
                const grid = document.getElementById('schedule-grid');
                grid.innerHTML = '';
                
                // Create time slots (8 AM to 9 PM)
                for (let hour = 8; hour <= 21; hour++) {
                    // Time column
                    const timeCol = document.createElement('div');
                    timeCol.className = 'border-r dark:border-gray-700 pr-2 text-right text-xs text-gray-500 dark:text-gray-400';
                    timeCol.textContent = formatTime(hour, 0);
                    grid.appendChild(timeCol);
                    
                    // Day columns
                    for (let day = 0; day < 7; day++) {
                        const cell = document.createElement('div');
                        cell.className = 'border-b border-r dark:border-gray-700 h-12 relative';
                        cell.dataset.hour = hour;
                        cell.dataset.day = day;
                        
                        // Add half-hour marker
                        const halfHourMarker = document.createElement('div');
                        halfHourMarker.className = 'border-t border-dashed border-gray-200 dark:border-gray-700 absolute w-full top-1/2';
                        cell.appendChild(halfHourMarker);
                        
                        grid.appendChild(cell);
                    }
                }
            }
            
            function populateEvents(lessons) {
                // Clear any existing event elements
                const existingEvents = document.querySelectorAll('.event-block');
                existingEvents.forEach(el => el.remove());
                
                // Filter lessons for current week
                const weekStart = formatDate(startOfWeek);
                const weekEnd = formatDate(new Date(startOfWeek.getTime() + 6 * 24 * 60 * 60 * 1000));
                
                const weekLessons = lessons.filter(lesson => {
                    const lessonDate = lesson.date;
                    return lessonDate >= weekStart && lessonDate <= weekEnd;
                });
                
                // Add event blocks for each lesson
                weekLessons.forEach(lesson => {
                    addEventToGrid(lesson);
                });
            }
            
            function addEventToGrid(lesson) {
                // Calculate day of week (0-6)
                const lessonDate = new Date(lesson.date);
                const dayOfWeek = daysBetween(startOfWeek, lessonDate);
                
                // Extract time and convert to number of minutes since 8 AM
                const [hours, minutes] = lesson.time.split(':').map(Number);
                const startMinutes = (hours - 8) * 60 + minutes;
                
                // Get event duration in minutes (default to 60 if not specified)
                const duration = lesson.duration || 60;
                
                // Create event element
                const eventEl = document.createElement('div');
                eventEl.className = `event-block absolute rounded-md p-2 overflow-hidden ${getStatusClass(lesson.status)}`;
                eventEl.style.left = 'calc(0.5rem)';
                eventEl.style.right = 'calc(0.5rem)';
                eventEl.style.top = `${startMinutes * 0.2}px`;  // 0.2px per minute (12px per hour)
                eventEl.style.height = `${duration * 0.2}px`;   // 0.2px per minute
                
                // Event content
                eventEl.innerHTML = `
                    <div class="font-semibold text-xs truncate">${lesson.time} - ${formatTimeFromMinutes(hours * 60 + minutes + duration)}</div>
                    <div class="text-xs truncate">${lesson.client}</div>
                    <div class="text-xs truncate">${lesson.lessonPackage?.name || 'Lesson'}</div>
                `;
                
                // Add click handler
                eventEl.addEventListener('click', () => showLessonDetails(lesson));
                
                // Find the right column for this day and append the event
                const cells = document.querySelectorAll(`[data-day="${dayOfWeek}"]`);
                if (cells.length > 0) {
                    const firstCell = cells[0];
                    const columnContainer = firstCell.parentElement;
                    columnContainer.appendChild(eventEl);
                }
            }
            
            function showLessonDetails(lesson) {
                const modal = document.getElementById('lesson-modal');
                const title = document.getElementById('modal-title');
                const content = document.getElementById('modal-content');
                const editLink = document.getElementById('edit-lesson-link');
                
                // Set title
                title.textContent = `Lesson with ${lesson.client}`;
                
                // Format lesson date/time
                const lessonDate = new Date(lesson.date + 'T' + lesson.time);
                const formattedDate = lessonDate.toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                
                // Format end time
                const [hours, minutes] = lesson.time.split(':').map(Number);
                const startMinutes = hours * 60 + minutes;
                const endTime = formatTimeFromMinutes(startMinutes + (lesson.duration || 60));
                
                // Create content HTML
                content.innerHTML = `
                    <div class="flex items-center mb-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            ${getStatusBadgeClass(lesson.status)}">
                            ${capitalizeFirstLetter(lesson.status.replace('_', ' '))}
                        </span>
                        <span class="ml-2 text-gray-600 dark:text-gray-400 text-sm">
                            ${formattedDate} at ${lesson.time} - ${endTime}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Customer</h4>
                            <p class="font-medium">${lesson.client}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">${lesson.clientEmail || ''}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">${lesson.phone || ''}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Location</h4>
                            <p>${lesson.location ? lesson.location.name : 'Not specified'}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Package</h4>
                            <p>${lesson.lessonPackage ? lesson.lessonPackage.name : 'Not specified'}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">${lesson.duration || 60} minutes</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Participants</h4>
                            <p>${lesson.participants}</p>
                        </div>
                    </div>
                    
                    ${lesson.notes ? `
                        <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Notes</h4>
                            <p class="mt-1 italic">${lesson.notes}</p>
                        </div>
                    ` : ''}
                `;
                
                // Set edit link
                editLink.href = `/admin/reservations/${lesson.id}/edit`;
                
                // Show the modal
                modal.classList.remove('hidden');
            }
            
            function closeModal() {
                const modal = document.getElementById('lesson-modal');
                modal.classList.add('hidden');
            }
            
            // Utility Functions
            function formatTime(hour, minute) {
                const period = hour >= 12 ? 'PM' : 'AM';
                const displayHour = hour % 12 === 0 ? 12 : hour % 12;
                return `${displayHour}${minute === 0 ? '' : ':' + minute.toString().padStart(2, '0')} ${period}`;
            }
            
            function formatTimeFromMinutes(totalMinutes) {
                const hour = Math.floor(totalMinutes / 60);
                const minute = totalMinutes % 60;
                return formatTime(hour, minute);
            }
            
            function getStartOfWeek(date) {
                const result = new Date(date);
                const day = result.getDay();
                result.setDate(result.getDate() - day);
                result.setHours(0, 0, 0, 0);
                return result;
            }
            
            function formatDate(date) {
                return date.toISOString().split('T')[0];
            }
            
            function daysBetween(date1, date2) {
                // Calculate days between two dates, ignoring time
                const d1 = new Date(date1);
                d1.setHours(0, 0, 0, 0);
                const d2 = new Date(date2);
                d2.setHours(0, 0, 0, 0);
                return Math.round((d2 - d1) / (24 * 60 * 60 * 1000));
            }
            
            function isToday(date) {
                const today = new Date();
                return date.getDate() === today.getDate() && 
                       date.getMonth() === today.getMonth() && 
                       date.getFullYear() === today.getFullYear();
            }
            
            function getStatusClass(status) {
                switch (status) {
                    case 'pending':
                        return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';
                    case 'confirmed':
                        return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
                    case 'completed':
                        return 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
                    case 'cancelled':
                        return 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
                    case 'instructor_cancelled':
                        return 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400';
                    case 'weather_cancelled':
                        return 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400';
                    default:
                        return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                }
            }
            
            function getStatusBadgeClass(status) {
                switch(status) {
                    case 'pending':
                        return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';
                    case 'confirmed':
                        return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
                    case 'completed':
                        return 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
                    case 'cancelled':
                        return 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
                    case 'instructor_cancelled':
                        return 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400';
                    case 'weather_cancelled':
                        return 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400';
                    default:
                        return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                }
            }
            
            function capitalizeFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }
        });
    </script>
    @endpush

    <style>
        /* Custom scrollbar for webkit browsers */
        .styled-scrollbars::-webkit-scrollbar {
            width: 4px;
        }
        
        .styled-scrollbars::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 10px;
        }
        
        .styled-scrollbars::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.15);
            border-radius: 10px;
        }
        
        .dark .styled-scrollbars::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .dark .styled-scrollbars::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
        }

        /* Define status colors for easier reference in inline styles */
        :root {
            --status-green: rgb(34, 197, 94);
            --status-yellow: rgb(234, 179, 8);
            --status-blue: rgb(59, 130, 246);
            --status-red: rgb(239, 68, 68);
            --status-orange: rgb(249, 115, 22);
            --status-purple: rgb(168, 85, 247);
            --status-gray: rgb(107, 114, 128);
        }
        
        /* Status-specific border colors */
        .border-status-confirmed { border-left-color: var(--status-green) !important; }
        .border-status-pending { border-left-color: var(--status-yellow) !important; }
        .border-status-completed { border-left-color: var(--status-blue) !important; }
        .border-status-cancelled { border-left-color: var(--status-red) !important; }
        .border-status-instructor_cancelled { border-left-color: var(--status-orange) !important; }
        .border-status-weather_cancelled { border-left-color: var(--status-purple) !important; }
    </style>
</x-app-layout>
