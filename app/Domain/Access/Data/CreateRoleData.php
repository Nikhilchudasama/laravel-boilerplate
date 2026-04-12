<?php

declare(strict_types=1);

namespace App\Domain\Access\Data;

use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class CreateRoleData extends Data
{
    public function __construct(
        #[Required, Unique('roles', 'name')]
        public string $name,

        /** @var array<string> */
        public array $permissions = [],
    ) {}
}
