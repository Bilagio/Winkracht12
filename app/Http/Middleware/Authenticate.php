<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // CRUCIAL: Don't redirect these paths to login
        if ($request->is('email/verify/*') || $request->is('set-password')) {
            return null;
        }
        
        return $request->expectsJson() ? null : route('login');
    }
}
