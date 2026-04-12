<?php

declare(strict_types=1);

namespace App\Domain\Access\Data;

use App\Domain\Access\Models\Role;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class UpdateRoleData extends Data
{
    public function __construct(
        #[Required]
        public string $name,

        /** @var array<string> */
        public array $permissions = [],
    ) {}

    public static function rules(?ValidationContext $context = null): array
    {
        $role = request()->route('role');
        $id = $role instanceof Role ? $role->id : $role;

        return [
            'name' => [
                'required',
                Rule::unique('roles', 'name')->ignore($id),
            ],
        ];
    }
}
