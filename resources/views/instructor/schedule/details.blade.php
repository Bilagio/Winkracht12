<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Lesson Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ $reservation->lessonPackage->name }} - {{ \Carbon\Carbon::parse($reservation->date)->format('M d, Y') }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Lesson #{{ $reservation->id }}
                        </p>
                    </div>
                    <div>
                        <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600">
                            Back to Schedule
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Lesson Details</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dl>
                                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Student</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ $reservation->user->name }}</dd>
                                </div>
                                <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ $reservation->user->email }}</dd>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Date & Time</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                        {{ \Carbon\Carbon::parse($reservation->date)->format('l, M d, Y') }} at {{ \Carbon\Carbon::parse($reservation->time)->format('g:i A') }}
                                    </dd>
                                </div>
                                <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Duration</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ $reservation->lessonPackage->duration }} minutes</dd>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">End Time</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                        {{ \Carbon\Carbon::parse($reservation->time)->addMinutes($reservation->lessonPackage->duration)->format('g:i A') }}
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <dl>
                                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Lesson Type</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ $reservation->lessonPackage->name }}</dd>
                                </div>
                                <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Location</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                        {{ $reservation->location->name }}<br>
                                        <span class="text-gray-600 dark:text-gray-400 text-xs">{{ $reservation->location->address }}, {{ $reservation->location->city }}</span>
                                    </dd>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Participants</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ $reservation->participants }}</dd>
                                </div>
                                <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Status</dt>
                                    <dd class="mt-1 sm:col-span-2 sm:mt-0">
                                        @if($reservation->status == 'confirmed')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200">
                                                Confirmed
                                            </span>
                                        @elseif($reservation->status == 'pending')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200">
                                                Pending
                                            </span>
                                        @elseif($reservation->status == 'cancelled')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200">
                                                Cancelled
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200">
                                                {{ ucfirst($reservation->status) }}
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                @if($reservation->notes)
                                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Special Requests</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ $reservation->notes }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
