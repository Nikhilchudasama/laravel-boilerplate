<?php

declare(strict_types=1);

namespace App\Domain\Activity\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

class Activity extends SpatieActivity
{
    use HasUuids;

    protected $keyType = 'string';

    public $incrementing = false;
}
