<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Throwable;

abstract class BaseQueries
{
    /**
     * Execute a database transaction.
     *
     * @throws Throwable
     */
    protected function transaction(callable $callback): mixed
    {
        return DB::transaction($callback);
    }
}
