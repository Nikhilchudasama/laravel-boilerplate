<?php

declare(strict_types=1);

namespace App\Support\Actions;

use Illuminate\Support\Facades\DB;

abstract class BaseAction
{
    /**
     * Execute a database transaction.
     */
    protected function transaction(callable $callback): mixed
    {
        return DB::transaction($callback);
    }
}
