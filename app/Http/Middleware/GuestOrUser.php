<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GuestOrUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Allow both guests and authenticated users to proceed
        return $next($request);
    }
}
