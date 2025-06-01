<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Reservation Details') }}
            </h2>
            <span class="px-2 py-1 text-xs rounded-full 
                @if($reservation->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                @elseif($reservation->status === 'confirmed') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 
                @elseif($reservation->status === 'completed') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                @elseif($reservation->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                @elseif($reservation->status === 'instructor_cancelled') bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400
                @elseif($reservation->status === 'weather_cancelled') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                @endif">
                {{ ucfirst(str_replace('_', ' ', $reservation->status)) }}
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium mb-1">Invoice #{{ $reservation->invoice_number }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Created on {{ $reservation->created_at->format('M d, Y \a\t H:i') }}
                            </p>
                        </div>
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($reservation->payment_status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                @elseif($reservation->payment_status === 'paid') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                @elseif($reservation->payment_status === 'refunded') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                @elseif($reservation->payment_status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                @endif">
                                Payment: {{ ucfirst($reservation->payment_status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium mb-4">Lesson Details</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Package</h4>
                                <p class="text-base">{{ $reservation->lessonPackage->name }}</p>
                                <p class="text-sm">Duration: {{ $reservation->lessonPackage->duration }} minutes</p>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Location</h4>
                                <p class="text-base">{{ $reservation->location->name }}</p>
                                <p class="text-sm">{{ $reservation->location->address }}, {{ $reservation->location->city }}</p>
                            </div>
                        </div>
                        
                        <div>
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Date & Time</h4>
                                <p class="text-base">{{ $reservation->date->format('l, F j, Y') }}</p>
                                <p class="text-sm">{{ \Carbon\Carbon::parse($reservation->time)->format('g:i A') }}</p>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Participants</h4>
                                <p class="text-base">{{ $reservation->participants }} {{ $reservation->participants > 1 ? 'people' : 'person' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Participants -->
            @if(is_array($reservation->additional_participants) && count($reservation->additional_participants) > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-4">Additional Participants</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($reservation->additional_participants as $index => $participant)
                                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <h4 class="font-medium mb-2">{{ $participant['name'] }}</h4>
                                    @if(!empty($participant['email']))
                                        <p class="text-sm">Email: {{ $participant['email'] }}</p>
                                    @endif
                                    @if(!empty($participant['phone']))
                                        <p class="text-sm">Phone: {{ $participant['phone'] }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Instructor Information (if assigned) -->
            @if($reservation->instructor)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-4">Your Instructor</h3>
                        
                        <div class="flex items-center">
                            <div class="h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-base font-medium">{{ $reservation->instructor->name }}</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Experienced Kitesurfing Instructor</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Payment Information -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium mb-4">Payment Information</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="text-left py-3 px-4 text-sm font-medium text-gray-500 dark:text-gray-400">Description</th>
                                    <th class="text-right py-3 px-4 text-sm font-medium text-gray-500 dark:text-gray-400">Price</th>
                                    <th class="text-right py-3 px-4 text-sm font-medium text-gray-500 dark:text-gray-400">Participants</th>
                                    <th class="text-right py-3 px-4 text-sm font-medium text-gray-500 dark:text-gray-400">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800">
                                <tr>
                                    <td class="py-2 px-4 text-sm">{{ $reservation->lessonPackage->name }}</td>
                                    <td class="py-2 px-4 text-sm text-right">€{{ number_format($reservation->lessonPackage->price, 2) }}</td>
                                    <td class="py-2 px-4 text-sm text-right">{{ $reservation->participants }}</td>
                                    <td class="py-2 px-4 text-sm text-right">€{{ number_format($reservation->total_price, 2) }}</td>
                                </tr>
                                <tr class="border-t border-gray-200 dark:border-gray-700">
                                    <td colspan="3" class="py-2 px-4 text-right font-medium">Total</td>
                                    <td class="py-2 px-4 text-right font-bold">€{{ number_format($reservation->total_price, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    @if($reservation->payment_status === 'pending')
                        <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-900/20 rounded-lg p-4">
                            <h4 class="font-medium mb-2 text-blue-800 dark:text-blue-300">Payment Instructions</h4>
                            <p class="text-sm mb-2">Please make your payment to the following bank account:</p>
                            <div class="flex flex-col space-y-1 text-sm">
                                <div class="flex justify-between">
                                    <span class="font-medium">Bank:</span>
                                    <span>Windkracht 13 Kitesurfing</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">IBAN:</span>
                                    <span>NL12 ABCD 0123 4567 89</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Reference:</span>
                                    <span>{{ $reservation->invoice_number }}</span>
                                </div>
                            </div>
                            <p class="mt-3 text-xs text-blue-700 dark:text-blue-400">
                                Your reservation will be confirmed once we receive your payment. Payment must be received within 5 days or your reservation may be cancelled.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="flex justify-between items-center">
                <a href="{{ route('customer.reservations.index') }}" class="inline-flex items-center text-sm text-blue-500 hover:text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back to Reservations
                </a>
                
                <button onclick="window.print()" class="inline-flex items-center px-3 py-2 text-sm font-medium bg-blue-500 hover:bg-blue-700 text-white rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                    </svg>
                    Print Invoice
                </button>
            </div>
        </div>
    </div>
</x-app-layout>
