<?php

declare(strict_types=1);

namespace App\Domain\Users\Listeners;

use App\Domain\Users\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Date;

class UpdateLastLoginDetails
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        /** @var User $user */
        $user = $event->user;

        if (method_exists($user, 'update')) {
            $user->update([
                'last_login_at' => Date::now(),
                'last_login_ip' => request()->ip(),
                'timezone' => request()->get('timezone') ?? $user->timezone,
            ]);
        }
    }
}
