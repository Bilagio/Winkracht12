@extends('layouts.guest')

@section('content')
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">{{ __('Register for Kitesurfing Lessons') }}</h2>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Enter your email to get started</p>
    </div>
    
    @if(session('error'))
        <div class="mb-4 font-medium text-sm text-red-600 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif
    
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 dark:text-gray-300" />
            <x-text-input id="email" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-[#3498db] focus:ring-[#3498db] rounded-md shadow-sm" type="email" name="email" :value="old('email')" required autofocus placeholder="your.email@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            
            @if($errors->has('email') && strpos($errors->first('email'), 'already registered') !== false)
                <p class="mt-2 text-sm text-blue-600">
                    <a href="{{ route('login') }}" class="font-medium underline">Login here</a> to access your account.
                </p>
            @endif
        </div>

        <div class="mt-4 text-sm text-gray-600 dark:text-gray-400 bg-gray-100/70 dark:bg-gray-800/70 p-3 rounded">
            <p>After registration:</p>
            <ol class="list-decimal ml-5 mt-1 space-y-1">
                <li>You'll receive a verification email</li>
                <li>Click the link in the email to verify your account</li>
                <li>Set your name and create a secure password</li>
            </ol>
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#3498db] to-[#2980b9] border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:from-[#2980b9] hover:to-[#2980b9] focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ __('Register') }}
            </button>
        </div>
    </form>
@endsection
