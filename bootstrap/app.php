<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// (optional) keep the use if you like, but we'll reference classes inline.
// use App\Http\Middleware\CompanyAccess;
// use App\Http\Middleware\EnsureSupport;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register route middleware aliases (Laravel 11/12 way):
        $middleware->alias([
            // Company-scoped access (use this on company routes)
            'company' => \App\Http\Middleware\CompanyAccess::class,

            // GS Auto support console (SuperAdmin + Service Client)
            'support' => \App\Http\Middleware\EnsureSupport::class,
        ]);

        // If you had global middleware or priority config, add here.
        // $middleware->append(...);
        // $middleware->prepend(...);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Your exception reporting/handling config if needed
    })
    ->create();