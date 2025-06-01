<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Instructor Details') }}
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
                    <a href="{{ route('admin.instructors.index') }}" class="inline-flex items-center text-sm text-blue-500 hover:text-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Back to Instructors
                    </a>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('admin.instructors.edit', $instructor) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        Edit Instructor
                    </a>
                    <a href="{{ route('admin.instructors.schedule', $instructor) }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                        View Schedule
                    </a>
                    <a href="{{ route('admin.instructors.confirm-delete', $instructor) }}" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                        Delete Instructor
                    </a>
                </div>
            </div>

            <!-- Instructor Information Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="md:flex md:items-start">
                        <!-- Instructor Avatar -->
                        <div class="md:flex-shrink-0 mb-4 md:mb-0 md:mr-6">
                            <div class="h-32 w-32 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-500 dark:text-blue-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-16 h-16">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Basic Information -->
                        <div class="md:flex-1">
                            <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $instructor->name }}</h3>
                            <p class="mt-1 text-gray-600 dark:text-gray-400">{{ $instructor->email }}</p>
                            
                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Contact Information</h4>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $instructor->mobile ?: 'Mobile not provided' }}</p>
                                    <p class="mt-0.5 text-gray-900 dark:text-gray-100">{{ $instructor->address ? $instructor->address . ', ' . $instructor->city : 'Address not provided' }}</p>
                                </div>
                                
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Professional Info</h4>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $instructor->experience_years ? $instructor->experience_years . ' years experience' : 'Experience not specified' }}</p>
                                    <p class="mt-0.5 text-gray-900 dark:text-gray-100">{{ $instructor->specialties ?: 'No specialties listed' }}</p>
                                </div>
                                
                                @if($instructor->bio)
                                <div class="md:col-span-2">
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Biography</h4>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $instructor->bio }}</p>
                                </div>
                                @endif
                                
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Date of Birth</h4>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $instructor->date_of_birth ? $instructor->date_of_birth->format('F j, Y') : 'Not provided' }}</p>
                                </div>
                                
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Account Created</h4>
                                    <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $instructor->created_at->format('F j, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Instructor Schedule Section -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                        Instructor Schedule
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('admin.instructors.schedule', [$instructor, 'view' => 'day']) }}" 
                           class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 flex flex-col items-center hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 mb-2 text-blue-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                            <span class="font-medium">Day View</span>
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center mt-1">View daily schedule</p>
                        </a>
                        
                        <a href="{{ route('admin.instructors.schedule', [$instructor, 'view' => 'week']) }}" 
                           class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 flex flex-col items-center hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 mb-2 text-blue-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-18 0V9.75A2.25 2.25 0 015.25 7.5H10" />
                            </svg>
                            <span class="font-medium">Week View</span>
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center mt-1">View weekly schedule</p>
                        </a>
                        
                        <a href="{{ route('admin.instructors.schedule', [$instructor, 'view' => 'month']) }}" 
                           class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 flex flex-col items-center hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 mb-2 text-blue-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0021 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-18 0V9.75A2.25 2.25 0 015.25 7.5H10V9.75A2.25 2.25 0 0112.25 7.5h1.5V9.75A2.25 2.25 0 0116 7.5h2.25" />
                            </svg>
                            <span class="font-medium">Month View</span>
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center mt-1">View monthly schedule</p>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Lessons Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Lesson History</h3>
                    
                    @if($lessons->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date & Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Package</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Location</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($lessons as $lesson)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($lesson->date)->format('M d, Y') }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $lesson->time }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $lesson->user->name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $lesson->user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $lesson->lessonPackage->name ?? 'Unknown Package' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $lesson->location->name ?? 'Unknown Location' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($lesson->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                                    @elseif($lesson->status == 'confirmed') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                                    @elseif($lesson->status == 'completed') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                                    @elseif($lesson->status == 'cancelled') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400 @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $lesson->status)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <a href="{{ route('admin.reservations.show', $lesson) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">View Details</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            No lessons scheduled for this instructor yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
