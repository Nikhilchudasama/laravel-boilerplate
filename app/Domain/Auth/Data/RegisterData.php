<?php

declare(strict_types=1);

namespace App\Domain\Auth\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class RegisterData extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $password_confirmation,
        public string $timezone,
    ) {}

    public static function rules(?ValidationContext $context = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'timezone' => ['required', 'string'],
            'terms' => ['accepted'],
        ];
    }
}
