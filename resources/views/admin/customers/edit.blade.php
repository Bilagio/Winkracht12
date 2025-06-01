<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Customer') }}
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
                    <a href="{{ route('admin.customers.show', $customer) }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300">
                        View Details
                    </a>
                    <a href="{{ route('admin.customers.confirm-delete', $customer) }}" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                        Delete Customer
                    </a>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('status'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('status') }}</span>
                        </div>
                    @endif
                    
                    <form method="post" action="{{ route('admin.customers.update', $customer) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-6">
                            <!-- Basic Information -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Basic Information</h3>
                                <div class="mt-5 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                    <div class="sm:col-span-3">
                                        <x-input-label for="name" :value="__('Full Name')" />
                                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $customer->name)" required autofocus autocomplete="name" />
                                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                    </div>

                                    <div class="sm:col-span-3">
                                        <x-input-label for="email" :value="__('Email')" />
                                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $customer->email)" required autocomplete="email" />
                                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                    </div>

                                    <div class="sm:col-span-3">
                                        <x-input-label for="password" :value="__('New Password (optional)')" />
                                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Leave blank to keep current password.
                                        </div>
                                        <x-input-error class="mt-2" :messages="$errors->get('password')" />
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contact Information -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Contact Information</h3>
                                <div class="mt-5 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                    <div class="sm:col-span-4">
                                        <x-input-label for="address" :value="__('Address')" />
                                        <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $customer->address)" autocomplete="street-address" />
                                        <x-input-error class="mt-2" :messages="$errors->get('address')" />
                                    </div>

                                    <div class="sm:col-span-2">
                                        <x-input-label for="city" :value="__('City')" />
                                        <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $customer->city)" autocomplete="address-level2" />
                                        <x-input-error class="mt-2" :messages="$errors->get('city')" />
                                    </div>

                                    <div class="sm:col-span-3">
                                        <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                                        <x-text-input 
                                            id="date_of_birth" 
                                            name="date_of_birth" 
                                            type="date" 
                                            class="mt-1 block w-full" 
                                            :value="old('date_of_birth', $customer->date_of_birth ? $customer->date_of_birth->format('Y-m-d') : '')"
                                            max="{{ now()->subYears(18)->format('Y-m-d') }}" 
                                            min="1950-01-01"
                                        />
                                        <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')" />
                                    </div>

                                    <div class="sm:col-span-3">
                                        <x-input-label for="mobile" :value="__('Mobile Phone')" />
                                        <x-text-input id="mobile" name="mobile" type="text" class="mt-1 block w-full" :value="old('mobile', $customer->mobile)" />
                                        <x-input-error class="mt-2" :messages="$errors->get('mobile')" />
                                    </div>
                                    
                                    <div class="sm:col-span-3">
                                        <x-input-label for="bsn" :value="__('BSN Number')" />
                                        <x-text-input id="bsn" name="bsn" type="text" class="mt-1 block w-full" :value="old('bsn', $customer->bsn)" inputmode="numeric" pattern="[0-9]*" placeholder="000000000" maxlength="9" />
                                        <x-input-error class="mt-2" :messages="$errors->get('bsn')" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <a href="{{ route('admin.customers.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Customer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
