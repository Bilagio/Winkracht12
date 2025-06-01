<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Important: Don't use auth middleware here
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('resend');
    }
    
    /**
     * Show verification sent page
     */
    public function show()
    {
        return view('auth.verification-sent');
    }

    /**
     * Mark the user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request)
    {
        // Log each step to diagnose issues
        Log::info('Email verification process started', [
            'id' => $request->route('id'),
            'hash' => $request->route('hash')
        ]);

        try {
            // Find user by ID from the URL
            $user = User::findOrFail($request->route('id'));
            
            Log::info('User found for verification', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            // Check if hash is valid
            if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
                Log::error('Invalid verification hash', [
                    'user_id' => $user->id
                ]);
                return redirect('/')->with('error', 'Invalid verification link.');
            }

            // Check if email is already verified
            if ($user->hasVerifiedEmail()) {
                Log::info('Email already verified', [
                    'user_id' => $user->id
                ]);
                return redirect('/login')->with('status', 'Email already verified. Please log in.');
            }

            // Mark as verified
            $user->markEmailAsVerified();
            
            Log::info('Email marked as verified successfully', [
                'user_id' => $user->id
            ]);

            // Store user ID in session for the set-password page
            session(['verified_user_id' => $user->id]);
            
            Log::info('User ID stored in session', [
                'session_id' => session()->getId(),
                'verified_user_id' => session('verified_user_id')
            ]);
            
            // Redirect to set-password page
            return redirect('/set-password');
            
        } catch (\Exception $e) {
            Log::error('Exception during verification', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect('/')->with('error', 'Verification failed. Please try again or contact support.');
        }
    }
    
    /**
     * Resend the verification email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'Verification link sent!');
    }
}
