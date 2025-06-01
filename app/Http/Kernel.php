<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        // TEMPORARILY DISABLED FOR DEVELOPMENT
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \App\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookies::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        // TEMPORARILY DISABLED FOR DEVELOPMENT
        // \App\Http\Middleware\CheckForMaintenanceMode::class,
        // \App\Http\Middleware\RedirectIfAuthenticated::class,
        // \App\Http\Middleware\SetLocale::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookies::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class, // Ensure this is uncommented
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
        'api' => [
            // TEMPORARILY DISABLED FOR DEVELOPMENT
            // 'throttle:60,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        // All middleware registrations kept but will be disabled in routes
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        'admin' => \App\Http\Middleware\IsAdmin::class,
        'instructor' => \App\Http\Middleware\IsInstructor::class,
        'customer' => \App\Http\Middleware\IsCustomer::class,
        'guest.or.user' => \App\Http\Middleware\GuestOrUser::class,
        'authenticated.users' => \App\Http\Middleware\AuthenticatedUsers::class,
    ];

    // If your Laravel version needs this property, keep it (uncomment)
    // protected $middlewareAliases = [
    //     // ...existing code...
    // ];
}