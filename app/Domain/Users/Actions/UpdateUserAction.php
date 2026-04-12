<?php

declare(strict_types=1);

namespace App\Domain\Users\Actions;

use App\Domain\Users\Data\UpdateUserData;
use App\Domain\Users\Models\User;
use App\Support\Actions\BaseAction;
use Illuminate\Support\Facades\Hash;

class UpdateUserAction extends BaseAction
{
    public function execute(User $user, UpdateUserData $data): bool
    {
        return $this->transaction(function () use ($user, $data) {
            $updateData = [
                'name' => $data->name,
                'email' => $data->email,
                'type' => $data->type,
                'active' => $data->active,
            ];

            if ($data->password) {
                $updateData['password'] = Hash::make($data->password);
            }

            $updated = $user->update($updateData);

            if ($updated) {
                $user->syncRoles($data->roles);
            }

            return $updated;
        });
    }
}
