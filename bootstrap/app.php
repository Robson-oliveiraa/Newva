<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'has.role' => \App\Http\Middleware\EnsureUserHasRole::class,
            'has.any.role' => \App\Http\Middleware\EnsureUserHasAnyRole::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'not.authenticated' => \App\Http\Middleware\EnsureUserNotAuthenticated::class,
            'throttle.auth' => \App\Http\Middleware\ThrottleAuthAttempts::class,
            'csrf.validate' => \App\Http\Middleware\ValidateCsrfToken::class,
            'route.access' => \App\Http\Middleware\CheckRouteAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
