<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class UnitTestCase extends BaseTestCase
{
    use CreatesApplication;
    use WipeDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
    }
}
