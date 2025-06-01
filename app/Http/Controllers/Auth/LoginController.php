<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // TEMPORARILY DISABLED MIDDLEWARE
        // $this->middleware('guest')->except('logout');
        // $this->middleware('auth')->only('logout');
    }
    
    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');
        
        if (isset($credentials['email'])) {
            $credentials['email'] = strtolower($credentials['email']);
        }
        
        return $credentials;
    }
    
    /**
     * The user has been authenticated.
     * Auto-verify existing accounts.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Auto-verify existing accounts
        $user->markEmailAsVerifiedIfOld();
        
        // Redirect based on user role
        if ($user->isAdmin()) {
            return redirect('/admin/dashboard');
        } elseif ($user->isInstructor()) {
            return redirect('/instructor/dashboard');
        }
        
        return redirect('/customer/home');
    }
}
