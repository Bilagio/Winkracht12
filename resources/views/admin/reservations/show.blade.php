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
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Reservation Details') }}
            </h2>
            <span class="px-2 py-1 text-xs rounded-full {{ getStatusBadgeClass($reservation->status) }}">
                {{ ucfirst(str_replace('_', ' ', $reservation->status)) }}
            </span>
        </div>
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
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-8">
                        <h3 class="text-lg font-medium mb-4">Reservation Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Customer</div>
                                    <div class="font-medium">{{ $reservation->user->name }}</div>
                                    <div class="text-sm">{{ $reservation->user->email }}</div>
                                    @if($reservation->user->mobile)
                                        <div class="text-sm">{{ $reservation->user->mobile }}</div>
                                    @endif
                                </div>

                                <div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Date & Time</div>
                                    <div class="font-medium">{{ \Carbon\Carbon::parse($reservation->date)->format('l, F j, Y') }}</div>
                                    <div>{{ \Carbon\Carbon::parse($reservation->time)->format('g:i A') }}</div>
                                </div>

                                <div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Lesson Package</div>
                                    <div class="font-medium">{{ $reservation->lessonPackage->name }}</div>
                                    <div class="text-sm">Duration: {{ $reservation->lessonPackage->duration }} minutes</div>
                                    <div class="text-sm">Price: €{{ number_format($reservation->total_price, 2) }}</div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Location</div>
                                    <div class="font-medium">{{ $reservation->location->name }}</div>
                                    <div class="text-sm">{{ $reservation->location->address }}</div>
                                    <div class="text-sm">{{ $reservation->location->city }}</div>
                                </div>

                                <div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Instructor</div>
                                    @if($reservation->instructor)
                                        <div class="font-medium">{{ $reservation->instructor->name }}</div>
                                    @else
                                        <div class="text-sm text-yellow-600 dark:text-yellow-400">No instructor assigned yet</div>
                                    @endif
                                </div>

                                <div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">Additional Information</div>
                                    <div>Participants: {{ $reservation->participants }}</div>
                                    @if($reservation->notes)
                                        <div class="mt-1 text-sm italic">{{ $reservation->notes }}</div>
                                    @else
                                        <div class="text-sm text-gray-500 dark:text-gray-400">No notes</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-lg font-medium mb-4">Payment Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Payment Status</div>
                                @if($reservation->status === 'confirmed')
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Confirmed
                                        </span>
                                    </div>
                                    @if($reservation->payment_confirmed_at)
                                        <div class="text-sm mt-1">
                                            Confirmed on {{ $reservation->payment_confirmed_at->format('M d, Y') }}
                                        </div>
                                    @endif
                                    @if($reservation->payment_method)
                                        <div class="text-sm">
                                            Method: {{ ucfirst(str_replace('_', ' ', $reservation->payment_method)) }}
                                        </div>
                                    @endif
                                    @if($reservation->payment_reference)
                                        <div class="text-sm">
                                            Reference: {{ $reservation->payment_reference }}
                                        </div>
                                    @endif
                                @else
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Pending
                                        </span>
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ route('admin.payments.form', $reservation) }}" class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-md">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Confirm Payment
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-4 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <a href="{{ route('admin.reservations.edit', $reservation) }}" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                            Edit Reservation
                        </a>

                        <!-- Payment Confirmation Button (only for pending reservations) -->
                        @if($reservation->status === 'pending')
                            <a href="{{ route('admin.payments.form', $reservation) }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Confirm Payment
                            </a>
                        @endif

                        <!-- Cancellation buttons - only show if reservation is not already cancelled or completed -->
                        @if(!in_array($reservation->status, ['cancelled', 'instructor_cancelled', 'weather_cancelled', 'completed']))
                            <div class="flex gap-4 ml-auto">
                                <a href="{{ route('admin.communication.illness.form', $reservation) }}" class="inline-flex items-center px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 dark:bg-red-900/30 dark:hover:bg-red-900/50 dark:text-red-400 border border-red-200 dark:border-red-900/50 rounded-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                    </svg>
                                    Cancel - Instructor Illness
                                </a>
                                <a href="{{ route('admin.communication.weather.form', $reservation) }}" class="inline-flex items-center px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 dark:text-blue-400 border border-blue-200 dark:border-blue-900/50 rounded-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15a4.5 4.5 0 004.5 4.5H18a3.75 3.75 0 001.332-7.257 3 3 0 00-3.758-3.848 5.25 5.25 0 00-10.233 2.33A4.502 4.502 0 002.25 15z" />
                                    </svg>
                                    Cancel - Weather (Wind > 10)
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
