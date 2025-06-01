<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cancellation Request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('admin.cancellations.index') }}" class="flex items-center text-blue-500 hover:text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back to Cancellation Requests
                </a>
            </div>
            
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-medium">Cancellation Details</h3>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $cancellation->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : '' }}
                                    {{ $cancellation->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                    {{ $cancellation->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : '' }}">
                                    {{ ucfirst($cancellation->status) }}
                                </span>
                            </div>
                            
                            <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Customer's Reason</h4>
                                <p class="mt-1">{{ $cancellation->reason }}</p>
                            </div>
                            
                            <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                                <div class="flex justify-between items-center">
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Requested On</h4>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $cancellation->created_at->format('M d, Y H:i') }}</span>
                                </div>
                            </div>
                            
                            @if ($cancellation->status !== 'pending')
                                <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Admin Response</h4>
                                    <p class="mt-1">{{ $cancellation->admin_comment ?? 'No comment provided.' }}</p>
                                    
                                    <div class="flex justify-between items-center mt-2">
                                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Reviewed On</h4>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $cancellation->reviewed_at->format('M d, Y H:i') }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium mb-4">Reservation Details</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Customer</h4>
                                    <p class="text-base">{{ $cancellation->reservation->user->name }}</p>
                                    <p class="text-sm">{{ $cancellation->reservation->user->email }}</p>
                                    @if ($cancellation->reservation->user->mobile)
                                        <p class="text-sm">{{ $cancellation->reservation->user->mobile }}</p>
                                    @endif
                                </div>
                                
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Package</h4>
                                    <p class="text-base">{{ $cancellation->reservation->lessonPackage->name }}</p>
                                    <p class="text-sm">{{ $cancellation->reservation->lessonPackage->duration }} minutes</p>
                                    <p class="text-sm font-medium">€{{ number_format($cancellation->reservation->total_price, 2) }}</p>
                                </div>
                                
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Date & Time</h4>
                                    <p class="text-base">{{ $cancellation->reservation->date->format('l, F j, Y') }}</p>
                                    <p class="text-sm">{{ $cancellation->reservation->time }}</p>
                                </div>
                                
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Location</h4>
                                    <p class="text-base">{{ $cancellation->reservation->location->name }}</p>
                                    <p class="text-sm">{{ $cancellation->reservation->location->city }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <!-- Action Panel -->
                    @if ($cancellation->status === 'pending')
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg sticky top-6">
                            <div class="p-6">
                                <h3 class="text-lg font-medium mb-4">Review Request</h3>
                                
                                <form action="{{ route('admin.cancellations.update', $cancellation) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <div class="mb-4">
                                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Decision <span class="text-red-500">*</span></label>
                                        <div class="flex space-x-4">
                                            <div class="flex items-center">
                                                <input type="radio" id="approve" name="status" value="approved" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-700">
                                                <label for="approve" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">
                                                    Approve
                                                </label>
                                            </div>
                                            <div class="flex items-center">
                                                <input type="radio" id="reject" name="status" value="rejected" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-700">
                                                <label for="reject" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">
                                                    Reject
                                                </label>
                                            </div>
                                        </div>
                                        @error('status')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-6">
                                        <label for="admin_comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Comment (Optional)</label>
                                        <textarea id="admin_comment" name="admin_comment" rows="3" class="block p-2.5 w-full rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Add a comment for the customer...">{{ old('admin_comment') }}</textarea>
                                        @error('admin_comment')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">
                                        Submit Decision
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium mb-4">Status</h3>
                                <div class="p-4 rounded-lg {{ $cancellation->status === 'approved' ? 'bg-green-50 dark:bg-green-900/20 text-green-800 dark:text-green-200' : 'bg-red-50 dark:bg-red-900/20 text-red-800 dark:text-red-200' }}">
                                    @if ($cancellation->status === 'approved')
                                        <div class="flex">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <div>
                                                <p class="font-medium">Cancellation Approved</p>
                                                <p class="text-sm mt-1">The customer can now reschedule their lesson.</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <div>
                                                <p class="font-medium">Cancellation Rejected</p>
                                                <p class="text-sm mt-1">The original booking remains valid.</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                                    <p>Reviewed by: {{ $cancellation->reviewer ? $cancellation->reviewer->name : 'Unknown' }}</p>
                                    <p>Date: {{ $cancellation->reviewed_at->format('M d, Y H:i') }}</p>
                                </div>
                                
                                <div class="mt-4">
                                    <a href="{{ route('admin.cancellations.index') }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Back to All Requests
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
