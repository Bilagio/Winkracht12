<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Book a Lesson') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Display any form errors here -->
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <strong class="font-bold">Please fix the following errors:</strong>
                    <ul class="mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <!-- Display session messages -->
            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('customer.reservations.store') }}" class="space-y-6" id="booking-form">
                        @csrf

                        
                        <!-- Lesson Package Selection -->
                        <div>
                            <h3 class="text-lg font-medium mb-4">1. Choose a Lesson Package</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($lessonPackages as $package)
                                <div class="relative">
                                    <input type="radio" name="lesson_package_id" id="package_{{ $package->id }}" value="{{ $package->id }}" class="absolute opacity-0 h-0 w-0 package-radio" required
                                        {{ old('lesson_package_id') == $package->id ? 'checked' : '' }}
                                        data-max-participants="{{ $package->max_participants }}"
                                        data-price="{{ $package->price }}"
                                        data-is-duo="{{ str_contains(strtolower($package->name), 'duo') ? 'true' : 'false' }}">
                                    <label for="package_{{ $package->id }}" class="block h-full p-6 border-2 rounded-lg cursor-pointer transition-colors
                                        {{ old('lesson_package_id') == $package->id 
                                            ? 'bg-blue-50 border-blue-500 dark:bg-blue-900/30 dark:border-blue-500' 
                                            : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                                        <div class="font-semibold text-lg text-blue-600 dark:text-blue-400 mb-2">{{ $package->name }}</div>
                                        <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">{{ $package->description }}</p>
                                        <div class="flex justify-between text-sm">
                                            <span>{{ floor($package->duration / 60) }}:{{ $package->duration % 60 ? sprintf('%02d', $package->duration % 60) : '00' }} hours</span>
                                            <span class="font-semibold">€{{ number_format($package->price, 2) }}</span>
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                            Max {{ $package->max_participants }} {{ $package->max_participants > 1 ? 'participants' : 'participant' }}
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
                                @foreach($locations as $location)
                                <div class="relative">
                                    <input type="radio" name="location_id" id="location_{{ $location->id }}" value="{{ $location->id }}" class="absolute opacity-0 h-0 w-0" required
                                        {{ old('location_id') == $location->id ? 'checked' : '' }}>
                                    <label for="location_{{ $location->id }}" class="block h-full p-6 border-2 rounded-lg cursor-pointer transition-colors
                                        {{ old('location_id') == $location->id 
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
                                <input 
                                    type="date" 
                                    name="date" 
                                    id="date" 
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" 
                                    required
                                    min="{{ \Carbon\Carbon::now()->addDays(7)->format('Y-m-d') }}"
                                    value="{{ old('date') }}"
                                >
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                    Please select a date at least one week from today.
                                </p>
                                @error('date')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <h3 class="text-lg font-medium mb-4">4. Choose a Time</h3>
                                <select name="time" id="time" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                                    <option value="">Select a time</option>
                                    @foreach($timeSlots as $value => $label)
                                        <option value="{{ $value }}" {{ old('time') == $value ? 'selected' : '' }}>
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
                                <button type="button" id="decrease-participants" class="p-2 rounded-l-md bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <input type="number" name="participants" id="participants" min="1" max="10" value="{{ old('participants', 1) }}" class="p-2 w-16 text-center border-y border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" required readonly>
                                <button type="button" id="increase-participants" class="p-2 rounded-r-md bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
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

                        <!-- Additional Participants Details -->
                        <div id="additional-participants-container" class="mt-8 hidden">
                            <h3 class="text-lg font-medium mb-4">6. Additional Participants Details</h3>
                            <div id="additional-participants-fields">
                                <!-- Fields will be generated dynamically -->
                            </div>
                        </div>

                        <!-- Special Requests -->
                        <div class="mt-8">
                            <h3 class="text-lg font-medium mb-4">7. Special Requests (Optional)</h3>
                            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" placeholder="Any special requests or information we should know...">{{ old('notes') }}</textarea>
                        </div>

                        <!-- Price Calculation -->
                        <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-medium">Total Price:</h3>
                                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400" id="total-price">€0.00</div>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                This price includes all equipment and instruction. Payment details will be sent via email after booking.
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-10">
                            <button type="submit" id="submit-button" class="w-full md:w-auto px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Complete Booking
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
            const packageInputs = document.querySelectorAll('input[name="lesson_package_id"]');
            const form = document.getElementById('booking-form');
            const submitButton = document.getElementById('submit-button');
            const timeFeedback = document.getElementById('time-feedback');
            const additionalParticipantsContainer = document.getElementById('additional-participants-container');
            const additionalParticipantsFields = document.getElementById('additional-participants-fields');
            const totalPriceElement = document.getElementById('total-price');
            
            let selectedPackagePrice = 0;
            let maxParticipants = 10;

            // Participant counter
            decreaseBtn.addEventListener('click', function() {
                const currentValue = parseInt(participantsInput.value);
                if (currentValue > 1) {
                    participantsInput.value = currentValue - 1;
                    updateAdditionalParticipantFields();
                    updateTotalPrice();
                }
            });

            increaseBtn.addEventListener('click', function() {
                const currentValue = parseInt(participantsInput.value);
                if (currentValue < maxParticipants) {
                    participantsInput.value = currentValue + 1;
                    updateAdditionalParticipantFields();
                    updateTotalPrice();
                }
            });

            // Set minimum date to one week from today
            const oneWeekFromNow = new Date();
            oneWeekFromNow.setDate(oneWeekFromNow.getDate() + 7);
            const minDate = oneWeekFromNow.toISOString().split('T')[0];
            dateInput.min = minDate;

            // Highlight selected lesson package
            packageInputs.forEach(radio => {
                radio.addEventListener('change', function() {
                    packageInputs.forEach(r => {
                        const label = document.querySelector(`label[for="${r.id}"]`);
                        if (r.checked) {
                            label.classList.add('bg-blue-50', 'border-blue-500');
                            label.classList.add('dark:bg-blue-900/30', 'dark:border-blue-500');
                            label.classList.remove('border-gray-200', 'dark:border-gray-700');
                            
                            // Update max participants based on selected package
                            maxParticipants = parseInt(r.dataset.maxParticipants) || 10;
                            selectedPackagePrice = parseFloat(r.dataset.price) || 0;
                            
                            // Ensure participants don't exceed max for this package
                            if (parseInt(participantsInput.value) > maxParticipants) {
                                participantsInput.value = maxParticipants;
                            }
                            
                            updateAdditionalParticipantFields();
                            updateTotalPrice();
                        } else {
                            label.classList.remove('bg-blue-50', 'border-blue-500');
                            label.classList.remove('dark:bg-blue-900/30', 'dark:border-blue-500');
                            label.classList.add('border-gray-200', 'dark:border-gray-700');
                        }
                    });
                    checkAvailability();
                });
                
                // Initialize selected package on page load if one is checked
                if (radio.checked) {
                    radio.dispatchEvent(new Event('change'));
                }
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
                    checkAvailability();
                });
                
                // Initialize selected location on page load if one is checked
                if (radio.checked) {
                    radio.dispatchEvent(new Event('change'));
                }
            });

            // Check availability when date or time changes
            dateInput.addEventListener('change', checkAvailability);
            timeInput.addEventListener('change', checkAvailability);

            // Function to check availability
            function checkAvailability() {
                const date = dateInput.value;
                const time = timeInput.value;
                const locationId = getSelectedValue(locationInputs);
                const lessonPackageId = getSelectedValue(packageInputs);

                if (!date || !time || !locationId || !lessonPackageId) {
                    return; // Skip if not all required fields are filled
                }

                // Reset time feedback
                timeFeedback.innerHTML = '';
                timeFeedback.className = 'text-sm mt-2';
                submitButton.disabled = false;

                // Make API call to check availability
                fetch(`/api/check-availability?date=${date}&time=${time}&location_id=${locationId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.available) {
                            timeFeedback.innerHTML = 'This time slot is already booked. Please choose another time.';
                            timeFeedback.className = 'text-sm text-red-500 mt-2';
                            submitButton.disabled = true;
                        } else {
                            timeFeedback.innerHTML = 'Time slot available!';
                            timeFeedback.className = 'text-sm text-green-500 mt-2';
                            submitButton.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error checking availability:', error);
                    });
            }
            
            // Function to update additional participant fields
            function updateAdditionalParticipantFields() {
                const participantsCount = parseInt(participantsInput.value);
                
                // Show/hide additional participants section
                if (participantsCount > 1) {
                    additionalParticipantsContainer.classList.remove('hidden');
                    additionalParticipantsFields.innerHTML = '';
                    
                    // Generate fields for each additional participant
                    for (let i = 1; i < participantsCount; i++) {
                        const participantHtml = `
                            <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <h4 class="font-medium mb-3">Additional Participant ${i}</h4>
                                <div class="space-y-4">
                                    <div>
                                        <label for="participant_${i}_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name *</label>
                                        <input type="text" id="participant_${i}_name" name="participant_${i}_name" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                                    </div>
                                    <div>
                                        <label for="participant_${i}_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                        <input type="email" id="participant_${i}_email" name="participant_${i}_email" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    </div>
                                    <div>
                                        <label for="participant_${i}_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                                        <input type="tel" id="participant_${i}_phone" name="participant_${i}_phone" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    </div>
                                </div>
                            </div>
                        `;
                        additionalParticipantsFields.insertAdjacentHTML('beforeend', participantHtml);
                    }
                } else {
                    additionalParticipantsContainer.classList.add('hidden');
                }
            }
            
            // Function to update total price display
            function updateTotalPrice() {
                const participantsCount = parseInt(participantsInput.value);
                const totalPrice = selectedPackagePrice * participantsCount;
                totalPriceElement.textContent = `€${totalPrice.toFixed(2)}`;
            }

            // Helper function to get selected radio value
            function getSelectedValue(radioInputs) {
                for (const input of radioInputs) {
                    if (input.checked) {
                        return input.value;
                    }
                }
                return null;
            }

            // Form validation
            form.addEventListener('submit', function(e) {
                // Check if date is set and is at least one week from today
                const selectedDate = new Date(dateInput.value);
                if (selectedDate < oneWeekFromNow) {
                    e.preventDefault();
                    alert('Please select a date at least one week from today.');
                    return false;
                }

                // Check if time is selected
                if (!timeInput.value) {
                    e.preventDefault();
                    alert('Please select a time for your lesson.');
                    return false;
                }
            });
            
            // Initialize additional participant fields on page load
            updateAdditionalParticipantFields();
            
            // Initialize total price on page load
            updateTotalPrice();
        });
    </script>
</x-app-layout>
