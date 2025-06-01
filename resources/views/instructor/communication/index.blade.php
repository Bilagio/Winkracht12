<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Student Communications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Upcoming Lessons</h3>
                    
                    @if($upcomingLessons->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">You don't have any upcoming lessons scheduled.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date & Time</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Lesson</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Location</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                    @foreach($upcomingLessons as $lesson)
                                        <tr class="{{ $loop->even ? 'bg-gray-50 dark:bg-gray-900/30' : '' }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium">{{ \Carbon\Carbon::parse($lesson->date)->format('D, M d, Y') }}</div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::parse($lesson->time)->format('g:i A') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium">{{ $lesson->user->name }}</div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400">{{ $lesson->user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm">{{ $lesson->lessonPackage->name }}</div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400">{{ $lesson->participants }} participant(s)</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm">{{ $lesson->location->name }}</div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400">{{ $lesson->location->city }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($lesson->status === 'confirmed')
                                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                                        Confirmed
                                                    </span>
                                                @elseif($lesson->status === 'pending')
                                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">
                                                        Pending
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                                        {{ ucfirst($lesson->status) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="flex space-x-2 justify-center">
                                                    <a href="{{ route('instructor.communication.illness.form', $lesson->id) }}" class="px-3 py-1 bg-red-100 text-red-700 hover:bg-red-200 rounded-md text-sm font-medium">
                                                        Report Illness
                                                    </a>
                                                    <a href="{{ route('instructor.communication.weather.form', $lesson->id) }}" class="px-3 py-1 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-md text-sm font-medium">
                                                        Weather Alert
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
