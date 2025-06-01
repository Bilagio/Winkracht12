<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Request Cancellation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('customer.reservations.show', $reservation) }}" class="flex items-center text-blue-500 hover:text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back to Reservation
                </a>
            </div>
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium mb-4">Reservation Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Package</h4>
                                <p class="text-base">{{ $reservation->lessonPackage->name }}</p>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Location</h4>
                                <p class="text-base">{{ $reservation->location->name }}</p>
                            </div>
                        </div>
                        
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Date & Time</h4>
                                <p class="text-base">{{ $reservation->date->format('l, F j, Y') }}</p>
                                <p class="text-sm">{{ $reservation->time }}</p>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Participants</h4>
                                <p class="text-base">{{ $reservation->participants }} {{ $reservation->participants > 1 ? 'people' : 'person' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium mb-6">Cancellation Request</h3>
                    
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 mb-6 text-yellow-800 dark:text-yellow-300 text-sm">
                        <div class="flex">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 flex-shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                            <div>
                                <p class="font-medium mb-1">Please Note:</p>
                                <ul class="list-disc list-inside space-y-1 pl-1">
                                    <li>Cancellations require admin approval before you can reschedule</li>
                                    <li>Please provide a valid reason for your cancellation</li>
                                    <li>You'll receive an email notification once your request is reviewed</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('customer.reservations.cancel.store', $reservation) }}" method="POST">
                        @csrf
                        
                        <div class="mb-6">
                            <label for="reason" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Reason for Cancellation <span class="text-red-500">*</span></label>
                            <textarea id="reason" name="reason" rows="4" class="block p-2.5 w-full rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Please explain why you need to cancel this reservation...">{{ old('reason') }}</textarea>
                            @error('reason')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <a href="{{ route('customer.reservations.show', $reservation) }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">Cancel</a>
                            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg">Submit Cancellation Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
