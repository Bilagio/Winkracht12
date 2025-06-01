<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Customer Details') }}
            </h2>
            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                Admin Portal
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-between">
                <div>
                    <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center text-sm text-blue-500 hover:text-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Back to Customers
                    </a>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('admin.customers.edit', $customer) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        Edit Customer
                    </a>
                    <a href="{{ route('admin.customers.confirm-delete', $customer) }}" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                        Delete Customer
                    </a>
                </div>
            </div>
            
            <!-- Customer Personal Information -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Personal Information</h3>
                    
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Full Name</dt>
                            <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $customer->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                            <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $customer->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</dt>
                            <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $customer->address ?: 'Not provided' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">City</dt>
                            <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $customer->city ?: 'Not provided' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date of Birth</dt>
                            <dd class="mt-1 text-gray-900 dark:text-gray-100">
                                {{ $customer->date_of_birth ? $customer->date_of_birth->format('F j, Y') : 'Not provided' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Mobile Phone</dt>
                            <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $customer->mobile ?: 'Not provided' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">BSN Number</dt>
                            <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $customer->bsn ?: 'Not provided' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Account Created</dt>
                            <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $customer->created_at->format('F j, Y \a\t g:i a') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Verified</dt>
                            <dd class="mt-1">
                                @if($customer->email_verified_at)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                        Verified on {{ $customer->email_verified_at->format('F j, Y') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                                        Not Verified
                                    </span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
            
            <!-- Customer Reservations/Lessons -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Lesson History</h3>
                        
                        <a href="{{ route('admin.reservations.create', ['customer_id' => $customer->id]) }}" class="inline-flex items-center text-sm bg-blue-500 hover:bg-blue-700 text-white py-2 px-3 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Book New Lesson
                        </a>
                    </div>
                    
                    @if($reservations->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Time</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Package</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Location</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($reservations as $reservation)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ \Carbon\Carbon::parse($reservation->date)->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $reservation->time }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $reservation->lessonPackage->name ?? 'Unknown Package' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $reservation->location->name ?? 'Unknown Location' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'yellow',
                                                        'confirmed' => 'green',
                                                        'cancelled' => 'red',
                                                        'completed' => 'blue',
                                                        'instructor_cancelled' => 'orange',
                                                        'weather_cancelled' => 'purple'
                                                    ];
                                                    $status = $reservation->status;
                                                    $color = $statusColors[$status] ?? 'gray';
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    bg-{{ $color }}-100 text-{{ $color }}-800 
                                                    dark:bg-{{ $color }}-900/50 dark:text-{{ $color }}-300">
                                                    {{ ucfirst($status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 dark:text-blue-400">
                                                <a href="{{ route('admin.reservations.show', $reservation) }}">
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <p>No lessons booked yet.</p>
                            <a href="{{ route('admin.reservations.create', ['customer_id' => $customer->id]) }}" class="mt-4 inline-block text-blue-600 dark:text-blue-400 hover:underline">
                                Book their first lesson
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
