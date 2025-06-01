<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/verification-sent';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                'unique:users,email'
            ],
        ], [
            'email.unique' => 'This email is already registered. Please login or use a different email address.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Create user with a temporary name and random password
        // Will be updated after email verification
        return User::create([
            'name' => 'New User', // Temporary name, will be updated by user
            'email' => $data['email'],
            'password' => Hash::make(Str::random(32)), // Temporary random password
            'role' => 'customer',
            'notification_preferences' => User::getDefaultNotificationPreferences(),
        ]);
    }
    
    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        Log::info('Registration attempt STARTED', [
            'email' => $request->email,
            'content_type' => $request->header('Content-Type'),
            'accept' => $request->header('Accept')
        ]);
        
        try {
            // Basic validation - only require email
            $validated = $request->validate([
                'email' => 'required|email|unique:users',
            ]);
            
            // Create with minimum required fields - ignore everything else
            $user = User::create([
                'name' => 'New User',
                'email' => $request->email,
                'password' => Hash::make($this->generateRandomPassword()),
                'role' => 'customer',
                'notification_preferences' => User::getDefaultNotificationPreferences(),
            ]);
            
            Log::info('User created', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            
            // Send verification email
            $user->sendEmailVerificationNotification();
            
            Log::info('Registration completed and verification sent');
            
            // Respond based on request type
            if ($request->expectsJson()) {
                return response()->json(['status' => 'success', 'message' => 'Registration successful']);
            }
            
            return redirect()->route('verification.sent')
                ->with('status', 'Registration successful! Please check your email for verification link.');
                
        } catch (\Exception $e) {
            Log::error('Registration error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['email' => 'Registration failed: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Generate a secure random temporary password
     * 
     * @return string
     */
    protected function generateRandomPassword()
    {
        // Generate a password with minimum 12 chars including uppercase, number and special char
        $password = Str::random(8) . 
                   chr(rand(65, 90)) . // Uppercase letter
                   rand(0, 9) . // Number
                   ['!', '@', '#', '$', '%', '^', '&', '*'][rand(0, 7)]; // Special char
                   
        return str_shuffle($password); // Shuffle to ensure randomness
    }
}
