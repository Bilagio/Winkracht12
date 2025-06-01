<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticatedUsers
{
    /**
     * Handle an incoming request.
     * Only allows customers, instructors, and admins to access the route.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Check if role methods exist before calling them
        $isCustomer = method_exists($user, 'isCustomer') && $user->isCustomer();
        $isInstructor = method_exists($user, 'isInstructor') && $user->isInstructor();
        $isAdmin = method_exists($user, 'isAdmin') && $user->isAdmin();
        
        // Remove the homepage redirect logic - we want all users to stay on homepage
        
        if ($isCustomer || $isInstructor || $isAdmin) {
            return $next($request);
        }
        
        return redirect()->back()->with('error', 'You do not have permission to access this page.');
    }
}
