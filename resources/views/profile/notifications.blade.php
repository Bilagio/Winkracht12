<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Notification Preferences') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ session('status') }}
                        </div>
                    @endif

                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Email Notifications') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Manage how we communicate with you via email.') }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('profile.notifications.update') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('patch')

                            <div class="space-y-4">
                                <!-- Email Reminders -->
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="email_reminders" name="email_reminders" type="checkbox" 
                                            class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-[#3498db] dark:text-[#5dade2] shadow-sm focus:ring-[#3498db] dark:focus:ring-[#5dade2]"
                                            {{ $user->notification_preferences['email_reminders'] ?? false ? 'checked' : '' }} value="1">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="email_reminders" class="font-medium text-gray-700 dark:text-gray-300">Lesson Reminders</label>
                                        <p class="text-gray-500 dark:text-gray-400">Receive email reminders before your scheduled kitesurfing lessons.</p>
                                    </div>
                                </div>

                                <!-- Weather Alerts -->
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="email_weather_alerts" name="email_weather_alerts" type="checkbox" 
                                            class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-[#3498db] dark:text-[#5dade2] shadow-sm focus:ring-[#3498db] dark:focus:ring-[#5dade2]"
                                            {{ $user->notification_preferences['email_weather_alerts'] ?? false ? 'checked' : '' }} value="1">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="email_weather_alerts" class="font-medium text-gray-700 dark:text-gray-300">Weather Alerts</label>
                                        <p class="text-gray-500 dark:text-gray-400">Receive notifications about weather conditions that may affect your lessons.</p>
                                    </div>
                                </div>

                                <!-- Marketing Emails -->
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="email_marketing" name="email_marketing" type="checkbox" 
                                            class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-[#3498db] dark:text-[#5dade2] shadow-sm focus:ring-[#3498db] dark:focus:ring-[#5dade2]"
                                            {{ $user->notification_preferences['email_marketing'] ?? false ? 'checked' : '' }} value="1">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="email_marketing" class="font-medium text-gray-700 dark:text-gray-300">Marketing & Promotions</label>
                                        <p class="text-gray-500 dark:text-gray-400">Receive updates about special offers, new services, and events.</p>
                                    </div>
                                </div>

                                <!-- Reminder Days Before -->
                                <div class="mt-6">
                                    <label for="reminder_days_before" class="block font-medium text-gray-700 dark:text-gray-300">
                                        Send Lesson Reminders
                                    </label>
                                    <select id="reminder_days_before" name="reminder_days_before" 
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 rounded-md">
                                        @for ($i = 1; $i <= 7; $i++)
                                            <option value="{{ $i }}" {{ ($user->notification_preferences['reminder_days_before'] ?? 2) == $i ? 'selected' : '' }}>
                                                {{ $i }} {{ $i === 1 ? 'day' : 'days' }} before my lesson
                                            </option>
                                        @endfor
                                    </select>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Choose how many days before your lesson you'd like to receive a reminder.
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <button type="submit" class="px-4 py-2 bg-[#3498db] hover:bg-[#2980b9] text-white font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3498db]">
                                    {{ __('Save Preferences') }}
                                </button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
