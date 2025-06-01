<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cancel Lesson - Weather Conditions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ url()->previous() }}" class="inline-flex items-center text-sm text-blue-500 hover:text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Lesson Information</h3>
                    
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Customer</p>
                            <p class="font-medium">{{ $reservation->user->name }}</p>
                            <p class="text-sm">{{ $reservation->user->email }}</p>
                        </div>
                        
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Date & Time</p>
                            <p class="font-medium">{{ \Carbon\Carbon::parse($reservation->date)->format('l, F j, Y') }}</p>
                            <p>{{ \Carbon\Carbon::parse($reservation->time)->format('g:i A') }}</p>
                        </div>
                    </div>

                    <!-- Weather Information -->
                    <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-900/30">
                        <h3 class="text-lg font-medium mb-3 text-blue-800 dark:text-blue-300">Current Weather Conditions</h3>
                        
                        <div class="flex items-center">
                            <div class="p-3 rounded-lg flex items-center mr-4 {{ $windForce > 10 ? 'bg-red-100 dark:bg-red-900/30' : 'bg-yellow-100 dark:bg-yellow-900/30' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 {{ $windForce > 10 ? 'text-red-500' : 'text-yellow-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>

                            <div>
                                <div class="text-lg font-semibold">Wind Force: <span class="{{ $windForce > 10 ? 'text-red-600 dark:text-red-400' : '' }}">{{ $windForce }} Beaufort</span></div>
                                @if($windForce > 10)
                                    <p class="text-sm text-red-600 dark:text-red-400">
                                        Wind force exceeds 10 Beaufort, which makes conditions unsafe for kitesurfing lessons.
                                    </p>
                                @else
                                    <p class="text-sm text-yellow-600 dark:text-yellow-400">
                                        Wind force is {{ $windForce }} Beaufort. Consider if conditions are safe for the lesson.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-6 bg-red-50 dark:bg-red-900/20 p-4 rounded-lg border border-red-100 dark:border-red-900/30">
                        <div class="flex items-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-red-600 dark:text-red-400 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                            <h3 class="text-lg font-medium text-red-800 dark:text-red-300">Confirm Cancellation</h3>
                        </div>
                        <p class="text-sm text-red-700 dark:text-red-400">
                            You are about to cancel this lesson due to unsafe weather conditions. This will send an automated email notification to the customer.
                            This action cannot be undone.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('admin.communication.weather.send', $reservation) }}">
                        @csrf
                        
                        <input type="hidden" name="wind_force" value="{{ $windForce }}">
                        
                        <div class="mb-6">
                            <label for="additional_notes" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Additional Notes (Optional)</label>
                            <textarea id="additional_notes" name="additional_notes" rows="4" class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:border-blue-500 focus:ring focus:ring-blue-500 dark:text-white"></textarea>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                These notes will be included in the email to the customer.
                            </p>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <a href="{{ url()->previous() }}" class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-600">
                                Cancel
                            </a>
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                Confirm Cancellation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
