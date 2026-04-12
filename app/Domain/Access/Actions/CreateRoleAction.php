<?php

declare(strict_types=1);

namespace App\Domain\Access\Actions;

use App\Domain\Access\Data\CreateRoleData;
use App\Domain\Access\Models\Role;
use App\Support\Actions\BaseAction;

class CreateRoleAction extends BaseAction
{
    public function execute(CreateRoleData $data): Role
    {
        return $this->transaction(function () use ($data) {
            $role = Role::create(['name' => $data->name]);

            if (! empty($data->permissions)) {
                $role->syncPermissions($data->permissions);
            }

            return $role;
        });
    }
}
