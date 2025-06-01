<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Manage Reservations') }}
            </h2>
            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                Admin Portal
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('status'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('status') }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">Reservation List</h3>
                        <a href="{{ route('admin.reservations.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            New Reservation
                        </a>
                    </div>

                    <!-- Filter Form -->
                    <form action="{{ route('admin.reservations.index') }}" method="GET" class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg flex flex-wrap gap-4">
                        <div class="w-full md:w-auto">
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                            <select name="status" id="status" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="instructor_cancelled" {{ request('status') == 'instructor_cancelled' ? 'selected' : '' }}>Instructor Cancelled</option>
                                <option value="weather_cancelled" {{ request('status') == 'weather_cancelled' ? 'selected' : '' }}>Weather Cancelled</option>
                            </select>
                        </div>

                        <div class="w-full md:w-auto">
                            <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date From</label>
                            <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>

                        <div class="w-full md:w-auto">
                            <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date To</label>
                            <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        </div>

                        <div class="w-full md:w-auto flex items-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded mr-2">
                                Filter
                            </button>
                            <a href="{{ route('admin.reservations.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 px-4 rounded">
                                Reset
                            </a>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-700">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date & Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Package</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Location</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Instructor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($reservations as $reservation)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium">{{ \Carbon\Carbon::parse($reservation->date)->format('M d, Y') }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $reservation->time }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm">{{ $reservation->user->name ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $reservation->user->email ?? '' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $reservation->lessonPackage->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $reservation->location->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $reservation->instructor->name ?? 'Not Assigned' }}</td>
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
                                                $color = $statusColors[$reservation->status] ?? 'gray';
                                            @endphp
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $color }}-100 text-{{ $color }}-800 dark:bg-{{ $color }}-900/30 dark:text-{{ $color }}-300">
                                                {{ ucfirst(str_replace('_', ' ', $reservation->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.reservations.show', $reservation) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                    View
                                                </a>
                                                <a href="{{ route('admin.reservations.edit', $reservation) }}" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                                    Edit
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            No reservations found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $reservations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
