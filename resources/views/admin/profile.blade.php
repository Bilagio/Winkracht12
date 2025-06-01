<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Owner Profile') }}
            </h2>
            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                Admin Portal
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="max-w-xl">
                        @if (session('status'))
                            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                                <span class="block sm:inline">{{ session('status') }}</span>
                            </div>
                        @endif

                        <h2 class="text-lg font-medium mb-6">
                            Personal Information
                        </h2>

                        <form method="post" action="{{ route('admin.profile.update') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('patch')

                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="email" />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>

                            <div>
                                <x-input-label for="address" :value="__('Address')" />
                                <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $user->address)" autocomplete="street-address" />
                                <x-input-error class="mt-2" :messages="$errors->get('address')" />
                            </div>

                            <div>
                                <x-input-label for="city" :value="__('City')" />
                                <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $user->city)" autocomplete="address-level2" />
                                <x-input-error class="mt-2" :messages="$errors->get('city')" />
                            </div>

                            <div>
                                <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                                <x-text-input 
                                    id="date_of_birth" 
                                    name="date_of_birth" 
                                    type="date" 
                                    class="mt-1 block w-full" 
                                    :value="old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '')"
                                    max="{{ now()->subYears(18)->format('Y-m-d') }}" 
                                    min="1950-01-01"
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')" />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">You must be at least 18 years old and born after 1950</p>
                            </div>

                            <div>
                                <x-input-label for="bsn" :value="__('BSN Number')" />
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input 
                                        type="text" 
                                        name="bsn" 
                                        id="bsn" 
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 placeholder-gray-400 dark:placeholder-gray-500"
                                        placeholder="000000000" 
                                        value="{{ old('bsn', $user->bsn) }}" 
                                        pattern="[0-9]*" 
                                        inputmode="numeric" 
                                        maxlength="9" 
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    />
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('bsn')" />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Your BSN is stored securely and used only for required administrative purposes.</p>
                            </div>

                            <div>
                                <x-input-label for="mobile" :value="__('Mobile Number')" />
                                <div class="mt-1">
                                    <div class="flex">
                                        <div class="w-full sm:w-1/2 md:w-1/3">
                                            <select 
                                                id="country_code" 
                                                name="country_code" 
                                                class="block w-full rounded-l-md border-r-0 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                            >
                                                @foreach ($countryCodes as $country)
                                                    <option value="{{ $country['code'] }}" {{ $countryCode === $country['code'] ? 'selected' : '' }}>
                                                        {{ $country['name'] }} ({{ $country['code'] }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <input 
                                            type="text" 
                                            id="phone_number" 
                                            name="phone_number" 
                                            value="{{ old('phone_number', $phoneNumber) }}" 
                                            class="flex-1 block rounded-r-md border-l-0 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                            placeholder="612345678" 
                                            inputmode="numeric"
                                            pattern="[0-9]*"
                                            maxlength="10"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                        />
                                    </div>
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Enter your phone number without leading zeros (e.g., "612345678" for Netherlands)</p>
                            </div>

                            <div class="flex items-center gap-4 mt-8">
                                <x-primary-button>{{ __('Save') }}</x-primary-button>
                                
                                @if (session('status') === 'profile-updated')
                                    <p
                                        x-data="{ show: true }"
                                        x-show="show"
                                        x-transition
                                        x-init="setTimeout(() => show = false, 2000)"
                                        class="text-sm text-gray-600 dark:text-gray-400"
                                    >{{ __('Saved.') }}</p>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
