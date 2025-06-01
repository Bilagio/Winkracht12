<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('My Reservations') }}
            </h2>
            <a href="{{ route('customer.reservations.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Book New Lesson
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            
            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($reservations->isEmpty())
                        <div class="text-center py-12">
                            <div class="inline-flex justify-center items-center w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-500 dark:text-blue-400 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold mb-2">No Reservations Yet</h3>
                            <p class="text-gray-600 dark:text-gray-400 max-w-md mx-auto mb-6">
                                You haven't made any reservations for kitesurfing lessons yet. Ready to catch some waves?
                            </p>
                            <a href="{{ route('customer.reservations.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Book Your First Lesson
                            </a>
                        </div>
                    @else
                        <!-- Sorting controls -->
                        <div class="flex flex-wrap gap-2 mb-4 items-center text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Sort by:</span>
                            
                            @php
                                // Default values if not set
                                $sort = $sort ?? 'date';
                                $direction = $direction ?? 'desc';
                            @endphp
                            
                            <a href="{{ route('customer.reservations.index', ['sort' => 'date', 'direction' => $sort === 'date' && $direction === 'asc' ? 'desc' : 'asc']) }}" 
                               class="px-3 py-1 rounded-md {{ $sort === 'date' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }} hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                                Date 
                                @if($sort === 'date')
                                    @if($direction === 'asc')
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="inline w-4 h-4">
                                            <path fill-rule="evenodd" d="M10 5a.75.75 0 01.75.75v6.638l1.96-2.158a.75.75 0 111.08 1.04l-3.25 3.5a.75.75 0 01-1.08 0l-3.25-3.5a.75.75 0 111.08-1.04l1.96 2.158V5.75A.75.75 0 0110 5z" clip-rule="evenodd" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="inline w-4 h-4">
                                            <path fill-rule="evenodd" d="M10 15a.75.75 0 01-.75-.75V7.612L7.29 9.77a.75.75 0 01-1.08-1.04l3.25-3.5a.75.75 0 011.08 0l3.25 3.5a.75.75 0 11-1.08 1.04l-1.96-2.158v6.638A.75.75 0 0110 15z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                @endif
                            </a>
                            
                            <a href="{{ route('customer.reservations.index', ['sort' => 'status', 'direction' => $sort === 'status' && $direction === 'asc' ? 'desc' : 'asc']) }}" 
                               class="px-3 py-1 rounded-md {{ $sort === 'status' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }} hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                                Status
                                @if($sort === 'status')
                                    @if($direction === 'asc')
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="inline w-4 h-4">
                                            <path fill-rule="evenodd" d="M10 5a.75.75 0 01.75.75v6.638l1.96-2.158a.75.75 0 111.08 1.04l-3.25 3.5a.75.75 0 01-1.08 0l-3.25-3.5a.75.75 0 111.08-1.04l1.96 2.158V5.75A.75.75 0 0110 5z" clip-rule="evenodd" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="inline w-4 h-4">
                                            <path fill-rule="evenodd" d="M10 15a.75.75 0 01-.75-.75V7.612L7.29 9.77a.75.75 0 01-1.08-1.04l3.25-3.5a.75.75 0 011.08 0l3.25 3.5a.75.75 0 11-1.08 1.04l-1.96-2.158v6.638A.75.75 0 0110 15z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                @endif
                            </a>
                            
                            <a href="{{ route('customer.reservations.index', ['sort' => 'price', 'direction' => $sort === 'price' && $direction === 'asc' ? 'desc' : 'asc']) }}" 
                               class="px-3 py-1 rounded-md {{ $sort === 'price' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }} hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                                Price
                                @if($sort === 'price')
                                    @if($direction === 'asc')
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="inline w-4 h-4">
                                            <path fill-rule="evenodd" d="M10 5a.75.75 0 01.75.75v6.638l1.96-2.158a.75.75 0 111.08 1.04l-3.25 3.5a.75.75 0 01-1.08 0l-3.25-3.5a.75.75 0 111.08-1.04l1.96 2.158V5.75A.75.75 0 0110 5z" clip-rule="evenodd" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="inline w-4 h-4">
                                            <path fill-rule="evenodd" d="M10 15a.75.75 0 01-.75-.75V7.612L7.29 9.77a.75.75 0 01-1.08-1.04l3.25-3.5a.75.75 0 011.08 0l3.25 3.5a.75.75 0 11-1.08 1.04l-1.96-2.158v6.638A.75.75 0 0110 15z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                @endif
                            </a>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date & Time</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Package</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Location</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Payment</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                    @foreach($reservations as $reservation)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="font-medium">{{ $reservation->date->format('M d, Y') }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $reservation->time }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($reservation->lessonPackage)
                                                    {{ $reservation->lessonPackage->name }}
                                                @else
                                                    <span class="text-red-500">Package Missing - please contact support</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($reservation->location)
                                                    {{ $reservation->location->name }}
                                                @else
                                                    <span class="text-red-500">Location Missing</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs rounded-full 
                                                    @if($reservation->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                                    @elseif($reservation->status === 'confirmed') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 
                                                    @elseif($reservation->status === 'completed') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                                    @elseif($reservation->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                                    @elseif($reservation->status === 'pending-reschedule') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                                                    @endif">
                                                    {{ str_replace(['_', '-'], ' ', ucfirst($reservation->status)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs rounded-full 
                                                    @if($reservation->payment_status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                                    @elseif($reservation->payment_status === 'paid') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                                    @elseif($reservation->payment_status === 'refunded') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                                    @endif">
                                                    {{ ucfirst($reservation->payment_status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap font-medium">
                                                €{{ number_format($reservation->total_price, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('customer.reservations.show', $reservation) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">View</a>
                                                
                                                @if($reservation->date > now() && !in_array($reservation->status, ['cancelled', 'instructor_cancelled', 'weather_cancelled', 'pending-reschedule']))
                                                    <a href="{{ route('customer.reservations.cancel', $reservation) }}" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Cancel</a>
                                                @endif
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
