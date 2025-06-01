<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create Reservation') }}
            </h2>
            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                Admin Portal
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

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('admin.reservations.store') }}" class="space-y-6">
                        @csrf

                        <!-- Customer Selection -->
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Customer</label>
                            <select id="user_id" name="user_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="">Select a customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ (old('user_id') == $customer->id || (isset($selectedCustomer) && $selectedCustomer->id == $customer->id)) ? 'selected' : '' }}>
                                        {{ $customer->name }} ({{ $customer->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Lesson Package Selection -->
                        <div>
                            <label for="lesson_package_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lesson Package</label>
                            <select id="lesson_package_id" name="lesson_package_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="">Select a package</option>
                                @foreach($lessonPackages as $package)
                                    <option value="{{ $package->id }}" {{ old('lesson_package_id') == $package->id ? 'selected' : '' }}>
                                        {{ $package->name }} - €{{ $package->price }} ({{ $package->duration }} min)
                                    </option>
                                @endforeach
                            </select>
                            @error('lesson_package_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location Selection -->
                        <div>
                            <label for="location_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                            <select id="location_id" name="location_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="">Select a location</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                        {{ $location->name }} - {{ $location->city }}
                                    </option>
                                @endforeach
                            </select>
                            @error('location_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date and Time Selection -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                                <input type="date" id="date" name="date" value="{{ old('date') }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                @error('date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Time</label>
                                <select id="time" name="time" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    <option value="">Select a time</option>
                                    <option value="09:00" {{ old('time') == '09:00' ? 'selected' : '' }}>09:00 AM</option>
                                    <option value="10:00" {{ old('time') == '10:00' ? 'selected' : '' }}>10:00 AM</option>
                                    <option value="11:00" {{ old('time') == '11:00' ? 'selected' : '' }}>11:00 AM</option>
                                    <option value="12:00" {{ old('time') == '12:00' ? 'selected' : '' }}>12:00 PM</option>
                                    <option value="13:00" {{ old('time') == '13:00' ? 'selected' : '' }}>01:00 PM</option>
                                    <option value="14:00" {{ old('time') == '14:00' ? 'selected' : '' }}>02:00 PM</option>
                                    <option value="15:00" {{ old('time') == '15:00' ? 'selected' : '' }}>03:00 PM</option>
                                    <option value="16:00" {{ old('time') == '16:00' ? 'selected' : '' }}>04:00 PM</option>
                                    <option value="17:00" {{ old('time') == '17:00' ? 'selected' : '' }}>05:00 PM</option>
                                </select>
                                @error('time')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Number of Participants and Instructor Selection -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="participants" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Number of Participants</label>
                                <input type="number" id="participants" name="participants" value="{{ old('participants', 1) }}" min="1" max="10" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                @error('participants')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="instructor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Instructor (Optional)</label>
                                <select id="instructor_id" name="instructor_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    <option value="">Assign later</option>
                                    @foreach($instructors as $instructor)
                                        <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>
                                            {{ $instructor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('instructor_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Status Selection -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <select id="status" name="status" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="instructor_cancelled" {{ old('status') == 'instructor_cancelled' ? 'selected' : '' }}>Instructor Cancelled</option>
                                <option value="weather_cancelled" {{ old('status') == 'weather_cancelled' ? 'selected' : '' }}>Weather Cancelled</option>
                            </select>
                            @error('status')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes (Optional)</label>
                            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md shadow-sm">
                                Create Reservation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
