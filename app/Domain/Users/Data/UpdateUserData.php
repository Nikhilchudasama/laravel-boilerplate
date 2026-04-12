<?php

declare(strict_types=1);

namespace App\Domain\Users\Data;

use App\Domain\Users\Models\User;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class UpdateUserData extends Data
{
    public function __construct(
        #[Required]
        public string $name,
        #[Required, Email]
        public string $email,
        #[Min(8)]
        public ?string $password = null,
        #[Required]
        public string $type = 'user',
        public bool $active = true,

        /** @var array<string> */
        public array $roles = [],
    ) {}

    public static function rules(?ValidationContext $context = null): array
    {
        $user = request()->route('user');
        $id = $user instanceof User ? $user->id : $user;

        return [
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($id),
            ],
        ];
    }
}
