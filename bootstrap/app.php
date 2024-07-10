<?php

use App\Http\Middleware\ProtectRoute;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api:__DIR__ . '/../routes/api.php',
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        apiPrefix: '/api'

    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            ProtectRoute::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
