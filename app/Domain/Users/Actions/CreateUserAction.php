<?php

declare(strict_types=1);

namespace App\Domain\Users\Actions;

use App\Domain\Users\Data\CreateUserData;
use App\Domain\Users\Models\User;
use App\Support\Actions\BaseAction;
use Illuminate\Support\Facades\Hash;

class CreateUserAction extends BaseAction
{
    public function execute(CreateUserData $data): User
    {
        return $this->transaction(function () use ($data) {
            $user = User::create([
                'name' => $data->name,
                'email' => $data->email,
                'password' => Hash::make($data->password),
                'type' => $data->type,
                'active' => $data->active,
            ]);

            if (! empty($data->roles)) {
                $user->syncRoles($data->roles);
            }

            return $user;
        });
    }
}
