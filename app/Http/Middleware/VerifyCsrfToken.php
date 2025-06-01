<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'email/verify/*',  // Skip CSRF for email verification
        'set-password',    // Skip CSRF for password setting
        'register',        // TEMP: Skip for register route to debug
    ];
}
