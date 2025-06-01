<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class SetPasswordController extends Controller
{
    /**
     * Show the form for setting a new password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showSetPasswordForm(Request $request)
    {
        // Get the user ID from session
        $userId = session('verified_user_id');
        
        Log::info('Set password form requested', [
            'session_id' => session()->getId(),
            'verified_user_id' => $userId
        ]);
        
        if (!$userId) {
            Log::warning('No verified_user_id in session', [
                'all_session' => session()->all()
            ]);
            return redirect('/login')->with('error', 'Your verification session has expired. Please try again.');
        }
        
        try {
            $user = User::findOrFail($userId);
            
            Log::info('Rendering set-password form for user', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            
            return view('auth.set-password', ['user' => $user]);
        } catch (\Exception $e) {
            Log::error('Error finding user for password setup', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            
            return redirect('/login')->with('error', 'User not found. Please try registering again.');
        }
    }
    
    /**
     * Set the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setPassword(Request $request)
    {
        // Get the user ID from session
        $userId = session('verified_user_id');
        
        Log::info('Set password submission received', [
            'session_id' => session()->getId(),
            'verified_user_id' => $userId
        ]);
        
        if (!$userId) {
            Log::warning('No verified_user_id in session during password set', [
                'all_session' => session()->all()
            ]);
            return redirect('/login')->with('error', 'Your session has expired. Please try again.');
        }
        
        try {
            $user = User::findOrFail($userId);
            
            // Validate the input
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'password' => [
                    'required',
                    'string',
                    'min:12',              // At least 12 characters
                    'confirmed',           // Must match password_confirmation field
                    'regex:/[A-Z]/',       // At least one uppercase letter
                    'regex:/[0-9]/',       // At least one number
                    'regex:/[^A-Za-z0-9]/' // At least one special character
                ],
            ], [
                'password.min' => 'The password must be at least 12 characters.',
                'password.regex' => 'The password must contain at least one uppercase letter, one number, and one special character.',
            ]);
            
            // Update user's name and password
            $user->name = $validated['name'];
            $user->password = Hash::make($validated['password']);
            $user->save();
            
            // Clean up session
            session()->forget('verified_user_id');
            
            // Log the user in
            Auth::login($user);
            
            Log::info('User password set and logged in successfully', [
                'user_id' => $user->id
            ]);
            
            return redirect('/customer/home')
                ->with('status', 'Registration complete! Welcome to Windkracht 13.');
                
        } catch (\Exception $e) {
            Log::error('Error during password setting', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors(['password' => 'An error occurred. Please try again.'])->withInput();
        }
    }
}
