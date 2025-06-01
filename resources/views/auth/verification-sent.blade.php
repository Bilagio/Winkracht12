@extends('layouts.guest')

@section('content')
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Check Your Email</h2>
        <p class="text-gray-600 dark:text-gray-400 mt-1">We've sent you a verification link to complete your registration</p>
    </div>
    
    <div class="bg-blue-50 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-6" role="alert">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
            </svg>
            <span>A verification link has been sent to your email address.</span>
        </div>
    </div>
    
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md p-6 rounded-lg shadow-lg">
        <div class="text-center mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 mx-auto text-[#3498db] dark:text-[#5dade2]">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
            </svg>
        </div>
        
        <ol class="list-decimal pl-5 space-y-3 text-gray-700 dark:text-gray-300">
            <li>Check your email inbox for a message from Windkracht 13</li>
            <li>Click on the "Verify Email Address" button in the email</li>
            <li>You'll be redirected to set your password</li>
            <li>After setting your password, you'll be logged in automatically</li>
        </ol>
        
        <div class="mt-6 text-sm text-gray-600 dark:text-gray-400">
            <p>If you don't see the email, check your spam folder or <a href="{{ route('welcome') }}" class="text-[#3498db] dark:text-[#5dade2] hover:underline">click here</a> to try again.</p>
        </div>
    </div>
    
    <div class="mt-6 text-center">
        <a href="{{ route('welcome') }}" class="text-[#3498db] dark:text-[#5dade2] hover:underline">Back to Home</a>
    </div>
@endsection
