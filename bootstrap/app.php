<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Middleware global (untuk semua request)
        $middleware->append([
            \App\Http\Middleware\CorsMiddleware::class,
        ]);

        // Middleware khusus grup 'api'
        $middleware->group('api', [
            \App\Http\Middleware\CorsMiddleware::class,
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            // 'throttle:api',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();