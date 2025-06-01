<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Weekly Schedule') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('instructor.schedule.daily') }}" class="px-3 py-2 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                    Daily View
                </a>
                <a href="{{ route('instructor.schedule.monthly') }}" class="px-3 py-2 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                    Monthly View
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                            Week of {{ $startDate->format('F j') }} - {{ $endDate->format('F j, Y') }}
                        </h2>
                        <div class="flex space-x-2">
                            <a href="{{ route('instructor.schedule.weekly', ['week' => $startDate->copy()->subWeek()->format('Y-m-d')]) }}" class="flex items-center px-3 py-2 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Previous Week
                            </a>
                            <a href="{{ route('instructor.schedule.weekly', ['week' => $startDate->copy()->addWeek()->format('Y-m-d')]) }}" class="flex items-center px-3 py-2 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                Next Week
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-7 gap-4">
                @for ($i = 0; $i < 7; $i++)
                    @php
                        $currentDay = $startDate->copy()->addDays($i);
                        $dayKey = $currentDay->format('Y-m-d');
                        $isToday = $currentDay->isToday();
                    @endphp
                    
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg {{ $isToday ? 'ring-2 ring-blue-500 dark:ring-blue-400' : '' }}">
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="font-medium text-gray-900 dark:text-gray-100">
                                {{ $currentDay->format('D') }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $currentDay->format('M j') }}
                            </p>
                        </div>
                        
                        <div class="p-4 h-80 overflow-y-auto">
                            @if (isset($schedule[$dayKey]) && count($schedule[$dayKey]) > 0)
                                <div class="space-y-3">
                                    @foreach ($schedule[$dayKey] as $lesson)
                                        <a href="{{ route('instructor.schedule.lesson', $lesson) }}" class="block p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                                            <div class="flex justify-between items-start">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($lesson->time)->format('g:i A') }}</p>
                                                <span class="px-1.5 py-0.5 text-xs rounded-full {{ $lesson->status === 'confirmed' ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300' }}">
                                                    {{ ucfirst($lesson->status) }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-800 dark:text-gray-200 mt-1">{{ $lesson->lessonPackage->name }}</p>
                                            <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 mr-1">
                                                    <path d="M10 8a3 3 0 100-6 3 3 0 000 6zM3.465 14.493a1.23 1.23 0 00.41 1.412A9.957 9.957 0 0010 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 00-13.074.003z" />
                                                </svg>
                                                {{ $lesson->user->name }}
                                            </div>
                                            <div class="mt-1 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 mr-1">
                                                    <path fill-rule="evenodd" d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 103 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 002.273 1.765 11.842 11.842 0 00.976.544l.062.029.018.008.006.003zM10 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" clip-rule="evenodd" />
                                                </svg>
                                                {{ $lesson->location->name }}
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center h-full text-gray-400 dark:text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-xs">No lessons</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</x-app-layout>
