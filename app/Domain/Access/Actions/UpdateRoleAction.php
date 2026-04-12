<?php

declare(strict_types=1);

namespace App\Domain\Access\Actions;

use App\Domain\Access\Data\UpdateRoleData;
use App\Domain\Access\Models\Role;
use App\Support\Actions\BaseAction;

class UpdateRoleAction extends BaseAction
{
    public function execute(Role $role, UpdateRoleData $data): bool
    {
        return $this->transaction(function () use ($role, $data) {
            $updated = $role->update(['name' => $data->name]);

            if ($updated) {
                $role->syncPermissions($data->permissions);
            }

            return $updated;
        });
    }
}
