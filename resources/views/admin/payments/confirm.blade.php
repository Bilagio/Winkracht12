<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Confirm Payment') }}
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
                    <h3 class="text-lg font-medium mb-4">Reservation Payment Confirmation</h3>
                    
                    <!-- Reservation Info Summary -->
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
                        
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Package</p>
                            <p class="font-medium">{{ $reservation->lessonPackage->name }}</p>
                            <p>{{ $reservation->participants }} {{ $reservation->participants > 1 ? 'participants' : 'participant' }}</p>
                        </div>
                        
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Payment Amount</p>
                            <p class="font-medium text-xl">€{{ number_format($reservation->total_price, 2) }}</p>
                            <p class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                Pending
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.payments.confirm', $reservation) }}">
                        @csrf
                        
                        <div class="space-y-6">
                            <!-- Payment Method -->
                            <div>
                                <label for="payment_method" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Payment Method</label>
                                <select id="payment_method" name="payment_method" class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                                    <option value="" disabled selected>Select payment method</option>
                                    <option value="cash">Cash</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="credit_card">Credit Card</option>
                                    <option value="ideal">iDEAL</option>
                                </select>
                                @error('payment_method')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Payment Amount -->
                            <div>
                                <label for="payment_amount" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Payment Amount (€)</label>
                                <input type="number" id="payment_amount" name="payment_amount" value="{{ $reservation->total_price }}" step="0.01" min="0" class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                                @error('payment_amount')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Payment Reference -->
                            <div>
                                <label for="payment_reference" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Payment Reference (Optional)</label>
                                <input type="text" id="payment_reference" name="payment_reference" class="block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Transaction ID, receipt number, or other reference</p>
                                @error('payment_reference')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Confirmation Notice -->
                        <div class="mt-8 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-900/30">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700 dark:text-blue-300">
                                        By confirming this payment, the reservation status will change from <strong>pending</strong> to <strong>confirmed</strong>. 
                                        An email notification will be sent to both the customer and the instructor (if assigned).
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-4">
                            <a href="{{ url()->previous() }}" class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-600">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md">
                                Confirm Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
