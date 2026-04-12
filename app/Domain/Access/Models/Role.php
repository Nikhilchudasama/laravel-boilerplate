<?php

declare(strict_types=1);

namespace App\Domain\Access\Models;

use App\Support\Traits\HasGlobalSearch;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasGlobalSearch;
    use HasUuids;
    use LogsActivity;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $searchable = ['name'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
