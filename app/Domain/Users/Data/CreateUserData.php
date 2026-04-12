<?php

declare(strict_types=1);

namespace App\Domain\Users\Data;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class CreateUserData extends Data
{
    public function __construct(
        #[Required]
        public string $name,
        #[Required, Email, Unique('users', 'email')]
        public string $email,
        #[Required, Min(8)]
        public string $password,
        #[Required]
        public string $type = 'user',
        public bool $active = true,

        /** @var array<string> */
        public array $roles = [],
    ) {}
}
