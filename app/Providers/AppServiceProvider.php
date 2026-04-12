<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Users\Listeners\UpdateLastLoginDetails;
use Illuminate\Auth\Events\Login;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configure rate limiting
        $this->configureRateLimiting();

        Gate::before(fn ($user, $ability): ?true => $user->hasRole('admin') ? true : null);

        Gate::define('access-admin-panel', fn ($user) => $user->isAdmin());

        Gate::define('viewPulse', fn ($user) => $user->hasRole('admin'));

        Gate::define('viewLogViewer', fn ($user) => $user->hasRole('admin'));

        Event::listen(
            Login::class,
            UpdateLastLoginDetails::class
        );
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', fn ($request) => Limit::perMinute(60)->by($request->user()?->id ?: $request->ip()));

        // Stricter rate limiting for login attempts
        RateLimiter::for('login', fn ($request) => Limit::perMinute(5)->by($request->ip()));

        // Rate limiting for registration
        RateLimiter::for('register', fn ($request) => Limit::perHour(3)->by($request->ip()));

        // Rate limiting for password reset requests
        RateLimiter::for('password-reset', fn ($request) => Limit::perHour(5)->by($request->ip()));
    }
}
