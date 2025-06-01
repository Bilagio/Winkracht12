<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Reservation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('customer.reservations.update', $reservation) }}"
                        class="space-y-6" id="booking-form">
                        @csrf
                        @method('PUT')

                        <!-- Lesson Package Selection -->
                        <div>
                            <h3 class="text-lg font-medium mb-4">1. Choose a Lesson Package</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach ($lessonPackages as $package)
                                    <div class="relative">
                                        <input type="radio" name="lesson_package_id" id="package_{{ $package->id }}"
                                            value="{{ $package->id }}" class="absolute opacity-0 h-0 w-0" required
                                            {{ old('lesson_package_id', $reservation->lesson_package_id) == $package->id ? 'checked' : '' }}>
                                        <label for="package_{{ $package->id }}"
                                            class="block h-full p-6 border-2 rounded-lg cursor-pointer transition-colors
                                        {{ old('lesson_package_id', $reservation->lesson_package_id) == $package->id
                                            ? 'bg-blue-50 border-blue-500 dark:bg-blue-900/30 dark:border-blue-500'
                                            : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                                            <div class="font-semibold text-lg text-blue-600 dark:text-blue-400 mb-2">
                                                {{ $package->name }}</div>
                                            <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">
                                                {{ $package->description }}</p>
                                            <div class="flex justify-between text-sm">
                                                <span>{{ $package->duration }} minutes</span>
                                                <span
                                                    class="font-semibold">€{{ number_format($package->price, 2) }}</span>
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                                Up to {{ $package->max_participants }} participants
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('lesson_package_id')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location Selection -->
                        <div class="mt-8">
                            <h3 class="text-lg font-medium mb-4">2. Select Location</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach ($locations as $location)
                                    <div class="relative">
                                        <input type="radio" name="location_id" id="location_{{ $location->id }}"
                                            value="{{ $location->id }}" class="absolute opacity-0 h-0 w-0" required
                                            {{ old('location_id', $reservation->location_id) == $location->id ? 'checked' : '' }}>
                                        <label for="location_{{ $location->id }}"
                                            class="block h-full p-6 border-2 rounded-lg cursor-pointer transition-colors
                                        {{ old('location_id', $reservation->location_id) == $location->id
                                            ? 'bg-blue-50 border-blue-500 dark:bg-blue-900/30 dark:border-blue-500'
                                            : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                                            <div class="font-semibold text-lg mb-2">{{ $location->name }}</div>
                                            <p class="text-sm text-gray-700 dark:text-gray-300 mb-1">{{ $location->address }}</p>
                                            <p class="text-sm font-medium text-blue-600 dark:text-blue-400 mb-2">{{ $location->city }}</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2">{{ $location->description }}</p>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('location_id')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Date & Time Selection -->
                        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-medium mb-4">3. Choose a Date</h3>
                                <div class="relative">
                                    <input 
                                        type="date" 
                                        name="date" 
                                        id="date" 
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" 
                                        required
                                        min="{{ \Carbon\Carbon::now()->addWeek()->format('Y-m-d') }}"
                                        max="{{ \Carbon\Carbon::now()->addMonths(3)->format('Y-m-d') }}"
                                        value="{{ old('date', $reservation->date->format('Y-m-d')) }}"
                                    >
                                    <div class="absolute right-2 top-[13px] text-gray-400 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                    Please select a date at least one week from today and within the next 3 months.
                                </p>
                                <div id="date-feedback" class="text-sm mt-2"></div>
                                @error('date')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <h3 class="text-lg font-medium mb-4">4. Choose a Time</h3>
                                <select name="time" id="time"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                    required>
                                    <option value="">Select a time</option>
                                    @foreach ($timeSlots as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('time', $reservation->time) == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="time-feedback" class="text-sm text-gray-500 dark:text-gray-400 mt-2"></div>
                                @error('time')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Number of Participants -->
                        <div class="mt-8">
                            <h3 class="text-lg font-medium mb-4">5. Number of Participants</h3>
                            <div class="flex items-center">
                                <button type="button" id="decrease-participants"
                                    class="p-2 rounded-l-md bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <input type="number" name="participants" id="participants" min="1"
                                    max="10" value="{{ old('participants', $reservation->participants) }}"
                                    class="p-2 w-16 text-center border-y border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                    required readonly>
                                <button type="button" id="increase-participants"
                                    class="p-2 rounded-r-md bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                For groups larger than 5, please contact us directly.
                            </p>
                            @error('participants')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Special Requests -->
                        <div class="mt-8">
                            <h3 class="text-lg font-medium mb-4">6. Special Requests (Optional)</h3>
                            <textarea name="notes" id="notes" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                placeholder="Any special requests or information we should know...">{{ old('notes', $reservation->notes) }}</textarea>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-10 flex flex-col sm:flex-row gap-4 sm:justify-between">
                            <a href="{{ route('customer.reservations.index') }}"
                                class="w-full sm:w-auto px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-300 font-medium rounded-md shadow-sm hover:bg-gray-300 dark:hover:bg-gray-600 text-center focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:focus:ring-gray-700">
                                Cancel
                            </a>
                            <button type="submit" id="submit-button"
                                class="w-full sm:w-auto px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Update Reservation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const decreaseBtn = document.getElementById('decrease-participants');
            const increaseBtn = document.getElementById('increase-participants');
            const participantsInput = document.getElementById('participants');
            const dateInput = document.getElementById('date');
            const timeInput = document.getElementById('time');
            const locationInputs = document.querySelectorAll('input[name="location_id"]');
            const lessonPackageInputs = document.querySelectorAll('input[name="lesson_package_id"]');
            const form = document.getElementById('booking-form');
            const submitButton = document.getElementById('submit-button');
            const timeFeedback = document.getElementById('time-feedback');

            // Participant counter
            decreaseBtn.addEventListener('click', function() {
                const currentValue = parseInt(participantsInput.value);
                if (currentValue > 1) {
                    participantsInput.value = currentValue - 1;
                }
            });

            increaseBtn.addEventListener('click', function() {
                const currentValue = parseInt(participantsInput.value);
                if (currentValue < 10) {
                    participantsInput.value = currentValue + 1;
                }
            });

            // Set minimum date to one week from today
            const oneWeekFromNow = new Date();
            oneWeekFromNow.setDate(oneWeekFromNow.getDate() + 7);
            const minDate = oneWeekFromNow.toISOString().split('T')[0];
            dateInput.min = minDate;

            // Set maximum date to 3 months from today
            const threeMonthsFromNow = new Date();
            threeMonthsFromNow.setMonth(threeMonthsFromNow.getMonth() + 3);
            const maxDate = threeMonthsFromNow.toISOString().split('T')[0];
            dateInput.max = maxDate;

            // Date input event listener
            dateInput.addEventListener('change', function() {
                const selectedDate = new Date(this.value);
                const dateFeedback = document.getElementById('date-feedback');
                
                // Clear any existing feedback
                dateFeedback.textContent = '';
                dateFeedback.className = 'text-sm mt-2';
                
                // Check if date is valid and at least one week in the future
                if (selectedDate < oneWeekFromNow) {
                    dateFeedback.textContent = 'Please select a date at least one week from today.';
                    dateFeedback.className = 'text-sm text-red-500 mt-2';
                    this.setCustomValidity('Please select a date at least one week from today.');
                } else if (selectedDate > threeMonthsFromNow) {
                    dateFeedback.textContent = 'Please select a date within the next 3 months.';
                    dateFeedback.className = 'text-sm text-red-500 mt-2';
                    this.setCustomValidity('Please select a date within the next 3 months.');
                } else {
                    // Date is valid
                    const day = selectedDate.getDay();
                    if (day === 0 || day === 6) {
                        dateFeedback.textContent = 'Weekend selected. Higher availability!';
                        dateFeedback.className = 'text-sm text-green-500 mt-2';
                    } else {
                        dateFeedback.textContent = 'Weekday selected.';
                        dateFeedback.className = 'text-sm text-blue-500 mt-2';
                    }
                    this.setCustomValidity('');
                }
            });

            // Highlight selected lesson package
            lessonPackageInputs.forEach(radio => {
                radio.addEventListener('change', function() {
                    lessonPackageInputs.forEach(r => {
                        const label = document.querySelector(`label[for="${r.id}"]`);
                        if (r.checked) {
                            label.classList.add('bg-blue-50', 'border-blue-500');
                            label.classList.add('dark:bg-blue-900/30', 'dark:border-blue-500');
                            label.classList.remove('border-gray-200', 'dark:border-gray-700');
                        } else {
                            label.classList.remove('bg-blue-50', 'border-blue-500');
                            label.classList.remove('dark:bg-blue-900/30', 'dark:border-blue-500');
                            label.classList.add('border-gray-200', 'dark:border-gray-700');
                        }
                    });
                });
            });

            // Highlight selected location
            locationInputs.forEach(radio => {
                radio.addEventListener('change', function() {
                    locationInputs.forEach(r => {
                        const label = document.querySelector(`label[for="${r.id}"]`);
                        if (r.checked) {
                            label.classList.add('bg-blue-50', 'border-blue-500');
                            label.classList.add('dark:bg-blue-900/30', 'dark:border-blue-500');
                            label.classList.remove('border-gray-200', 'dark:border-gray-700');
                        } else {
                            label.classList.remove('bg-blue-50', 'border-blue-500');
                            label.classList.remove('dark:bg-blue-900/30', 'dark:border-blue-500');
                            label.classList.add('border-gray-200', 'dark:border-gray-700');
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>
