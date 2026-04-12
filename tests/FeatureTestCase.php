<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class FeatureTestCase extends BaseTestCase
{
    use CreatesApplication;
    use LazilyRefreshDatabase;
}
