@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col items-center">
        <div class="w-full max-w-md">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-[#3498db]/10 dark:bg-[#3498db]/20 border-b border-gray-200 dark:border-gray-700">
                    <h1 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Log in to your account') }}
                    </h1>
                </div>

                <div class="p-6">
                    @if ($errors->any())
                        <div class="mb-4">
                            <div class="font-medium text-red-600 dark:text-red-400">
                                {{ __('Whoops! Something went wrong.') }}
                            </div>

                            <ul class="mt-3 list-disc list-inside text-sm text-red-600 dark:text-red-400">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Email') }}
                            </label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-[#3498db] focus:ring focus:ring-[#3498db] focus:ring-opacity-50"
                            />
                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Password') }}
                            </label>
                            <input id="password" type="password" name="password" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-[#3498db] focus:ring focus:ring-[#3498db] focus:ring-opacity-50"
                            />
                        </div>

                        <!-- Remember Me -->
                        <div class="mt-4 flex items-center">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-[#3498db] shadow-sm focus:border-[#3498db] focus:ring focus:ring-[#3498db] focus:ring-opacity-50"
                            />
                            <label for="remember_me" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                {{ __('Remember me') }}
                            </label>
                        </div>

                        <div class="mt-6 flex items-center justify-between">
                            @if (Route::has('password.request'))
                                <a class="text-sm text-[#3498db] dark:text-[#5dade2] hover:underline" href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif

                            <button type="submit" class="ml-4 py-2 px-4 bg-[#3498db] hover:bg-[#2980b9] text-white font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3498db]">
                                {{ __('Log in') }}
                            </button>
                        </div>
                        
                        <div class="mt-6 text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Don\'t have an account?') }}
                                <a href="{{ route('register') }}" class="text-[#3498db] dark:text-[#5dade2] hover:underline">
                                    {{ __('Register') }}
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
