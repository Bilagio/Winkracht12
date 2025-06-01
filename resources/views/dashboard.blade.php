<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <!-- Book a Lesson button removed from header -->
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Card -->
            <div class="bg-gradient-to-r from-[#3498db] to-[#2980b9] text-white rounded-lg shadow-lg mb-8">
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div class="mb-6 md:mb-0">
                            <h3 class="text-2xl font-bold">Welcome, {{ Auth::user()->name }}!</h3>
                            <p class="mt-2 text-blue-100">Ready to catch some waves? Your kitesurfing journey starts here.</p>
                        </div>
                        <a href="{{ route('booking') }}" class="px-6 py-3 bg-white text-[#3498db] hover:bg-blue-50 font-semibold rounded-lg shadow transition-colors duration-200 inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Book a Lesson
                        </a>
                    </div>
                </div>
            </div>

            <!-- Dashboard Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Upcoming Lessons Card -->
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-lg shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">Upcoming Lessons</h3>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-[#3498db]">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400">You can view and manage your upcoming kitesurfing lessons from here.</p>
                        @if(Auth::user()->isCustomer()) 
                            <a href="{{ route('customer.reservations.index') }}" class="block text-[#3498db] dark:text-[#5dade2] hover:underline mt-4">
                                View your reservations →
                            </a>
                        @elseif(Auth::user()->isInstructor())
                            <a href="{{ route('instructor.schedule') }}" class="block text-[#3498db] dark:text-[#5dade2] hover:underline mt-4">
                                View your schedule →
                            </a>
                        @elseif(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="block text-[#3498db] dark:text-[#5dade2] hover:underline mt-4">
                                Manage reservations →
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Weather Card -->
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-lg shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">Weather Forecast</h3>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-[#3498db]">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15a4.5 4.5 0 004.5 4.5H18a3.75 3.75 0 001.332-7.257 3 3 0 00-3.758-3.848 5.25 5.25 0 00-10.233 2.33A4.502 4.502 0 002.25 15z" />
                            </svg>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400">Check wind conditions before your next kitesurfing lesson.</p>
                        <a href="https://www.windfinder.com/" target="_blank" class="block text-[#3498db] dark:text-[#5dade2] hover:underline mt-4">
                            View forecast →
                        </a>
                    </div>
                </div>

                <!-- Profile Card -->
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-lg shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">Your Profile</h3>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-[#3498db]">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400">Update your personal information and account settings.</p>
                        <a href="{{ route('profile.edit') }}" class="block text-[#3498db] dark:text-[#5dade2] hover:underline mt-4">
                            Edit profile →
                        </a>
                        
                        <!-- Add logout button -->
                        <form method="POST" action="{{ route('logout') }}" class="mt-3">
                            @csrf
                            <button type="submit" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 text-sm">
                                <span class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                    </svg>
                                    Log out
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
