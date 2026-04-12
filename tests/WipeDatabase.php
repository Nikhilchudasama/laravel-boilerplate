<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Facades\Artisan;

trait WipeDatabase
{
    private static bool $dbWiped = false;

    protected function setUp(): void
    {
        parent::setUp();

        if (static::$dbWiped) {
            return;
        }

        if (app()->environment('testing')) {
            Artisan::call('db:wipe');
        }

        static::$dbWiped = true;
    }
}
