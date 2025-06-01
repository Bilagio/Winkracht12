<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Simplify the check to avoid extra method calls that could cause issues
        if (!Auth::check() || Auth::user()->role !== 'customer') {
            return redirect('/')->with('error', 'You do not have customer access.');
        }

        return $next($request);
    }
}
