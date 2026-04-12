<?php

declare(strict_types=1);

namespace App\Domain\Common\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;

class Media extends SpatieMedia
{
    use HasUuids;

    protected $keyType = 'string';

    public $incrementing = false;
}
