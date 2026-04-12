<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->prefix('admin')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'password.expired' => \App\Http\Middleware\PasswordExpiration::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \App\Http\Middleware\SecurityHeaders::class,
        ]);

        $middleware->redirectTo(
            guests: function ($request) {
                if ($request->is('admin/*') || $request->is('admin')) {
                    return route('admin.login');
                }

                return route('login');
            }
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
