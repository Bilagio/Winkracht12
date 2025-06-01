<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Add New Instructor') }}
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
                    <form method="post" action="{{ route('admin.instructors.store') }}" class="space-y-6">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Account Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="name" :value="__('Full Name')" />
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>
                                
                                <div>
                                    <x-input-label for="email" :value="__('Email Address')" />
                                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                </div>

                                <div>
                                    <x-input-label for="password" :value="__('Password (optional)')" />
                                    <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" />
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Leave empty to auto-generate a password.
                                    </p>
                                    <x-input-error class="mt-2" :messages="$errors->get('password')" />
                                </div>

                                <div>
                                    <x-input-label for="mobile" :value="__('Mobile Phone')" />
                                    <x-text-input id="mobile" name="mobile" type="text" class="mt-1 block w-full" :value="old('mobile')" />
                                    <x-input-error class="mt-2" :messages="$errors->get('mobile')" />
                                </div>
                            </div>
                        </div>
                        
                        <!-- Personal Information -->
                        <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Personal Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="address" :value="__('Address')" />
                                    <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address')" />
                                    <x-input-error class="mt-2" :messages="$errors->get('address')" />
                                </div>
                                
                                <div>
                                    <x-input-label for="city" :value="__('City')" />
                                    <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city')" />
                                    <x-input-error class="mt-2" :messages="$errors->get('city')" />
                                </div>
                                
                                <div>
                                    <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                                    <x-text-input 
                                        id="date_of_birth" 
                                        name="date_of_birth" 
                                        type="date" 
                                        class="mt-1 block w-full" 
                                        :value="old('date_of_birth')"
                                        max="{{ now()->subYears(18)->format('Y-m-d') }}" 
                                        min="1950-01-01"
                                    />
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Must be at least 18 years old</p>
                                    <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')" />
                                </div>
                            </div>
                        </div>
                        
                        <!-- Professional Information -->
                        <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Professional Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <x-input-label for="bio" :value="__('Biography')" />
                                    <textarea
                                        id="bio"
                                        name="bio"
                                        rows="4"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                                    >{{ old('bio') }}</textarea>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">A brief description of the instructor's background and teaching style</p>
                                    <x-input-error class="mt-2" :messages="$errors->get('bio')" />
                                </div>
                                
                                <div>
                                    <x-input-label for="specialties" :value="__('Specialties')" />
                                    <x-text-input id="specialties" name="specialties" type="text" class="mt-1 block w-full" :value="old('specialties')" />
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">E.g., Beginners, Advanced Techniques, Wave Riding</p>
                                    <x-input-error class="mt-2" :messages="$errors->get('specialties')" />
                                </div>
                                
                                <div>
                                    <x-input-label for="experience_years" :value="__('Years of Experience')" />
                                    <x-text-input id="experience_years" name="experience_years" type="number" min="0" max="50" class="mt-1 block w-full" :value="old('experience_years')" />
                                    <x-input-error class="mt-2" :messages="$errors->get('experience_years')" />
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-4">
                            <a href="{{ route('admin.instructors.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:bg-gray-300 dark:focus:bg-gray-600 active:bg-gray-300 dark:active:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
                                Cancel
                            </a>
                            
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
                                Create Instructor
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
