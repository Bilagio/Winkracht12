<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Daily Schedule') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('instructor.schedule.weekly') }}" class="px-3 py-2 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                    Weekly View
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
                            {{ $date->format('l, F j, Y') }}
                        </h2>
                        <div class="flex space-x-2">
                            <a href="{{ route('instructor.schedule.daily', ['date' => $date->copy()->subDay()->format('Y-m-d')]) }}" class="flex items-center px-3 py-2 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Previous Day
                            </a>
                            <a href="{{ route('instructor.schedule.daily', ['date' => $date->copy()->addDay()->format('Y-m-d')]) }}" class="flex items-center px-3 py-2 text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                Next Day
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @if($lessons->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <div class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-1">No Lessons Scheduled</h3>
                        <p class="text-gray-500 dark:text-gray-400">You have no lessons scheduled for this day.</p>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($lessons as $lesson)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                            <div class="border-l-4 border-blue-500 p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="flex items-center mb-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($lesson->time)->format('g:i A') }}</span>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $lesson->lessonPackage->name }}</h3>
                                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                            <p><span class="font-medium">Student:</span> {{ $lesson->user->name }}</p>
                                            <p><span class="font-medium">Location:</span> {{ $lesson->location->name }}</p>
                                            <p><span class="font-medium">Duration:</span> {{ $lesson->lessonPackage->duration }} minutes</p>
                                            <p><span class="font-medium">Participants:</span> {{ $lesson->participants }}</p>
                                            @if($lesson->notes)
                                                <p class="mt-2"><span class="font-medium">Notes:</span> {{ $lesson->notes }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <a href="{{ route('instructor.schedule.lesson', $lesson) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 dark:text-blue-400 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
