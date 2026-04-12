<?php

declare(strict_types=1);

namespace App\Domain\Access\Http\Controllers;

use App\Domain\Access\Actions\CreateRoleAction;
use App\Domain\Access\Actions\UpdateRoleAction;
use App\Domain\Access\Data\CreateRoleData;
use App\Domain\Access\Data\UpdateRoleData;
use App\Domain\Access\Exports\RolesExport;
use App\Domain\Access\Models\Permission;
use App\Domain\Access\Models\Role;
use App\Domain\Access\RoleQueries;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

class RoleController extends Controller
{
    public function __construct(
        protected RoleQueries $roleQueries
    ) {}

    public function index(Request $request)
    {
        $this->authorize('view_roles');

        $filterData = [
            'search_text' => $request->get('search_text'),
            'sort_by' => $request->get('sort_by'),
            'sort_direction' => $request->get('sort_direction'),
            'per_page' => $request->get('per_page'),
        ];

        $lengthAwarePaginator = $this->roleQueries->listQuery($filterData);

        if ($request->wantsJson()) {
            return [
                'total_records' => $lengthAwarePaginator->total(),
                'data' => $lengthAwarePaginator->getCollection(),
            ];
        }

        return Inertia::render('Admin/Roles/Index', [
            'roles' => $lengthAwarePaginator,
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create_roles');

        return Inertia::render('Admin/Roles/RoleForm', [
            'role' => null,
            'permissions' => Permission::all(),
        ]);
    }

    public function store(CreateRoleData $data, CreateRoleAction $action): RedirectResponse
    {
        $this->authorize('create_roles');

        $action->execute($data);

        return to_route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function edit(Role $role): Response
    {
        $this->authorize('edit_roles');

        return Inertia::render('Admin/Roles/RoleForm', [
            'role' => $role->load('permissions'),
            'permissions' => Permission::all(),
        ]);
    }

    public function update(Role $role, UpdateRoleData $data, UpdateRoleAction $action): RedirectResponse
    {
        $this->authorize('edit_roles');

        $action->execute($role, $data);

        return to_route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function export(Request $request)
    {
        $filterData = [
            'search_text' => $request->get('search_text'),
            'sort_by' => $request->get('sort_by'),
            'sort_direction' => $request->get('sort_direction'),
        ];

        return Excel::download(
            new RolesExport($this->roleQueries, $filterData),
            'roles.xlsx'
        );
    }

    public function bulkDelete(Request $request): RedirectResponse
    {
        $this->authorize('delete_roles');

        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return back()->with('error', 'No roles selected.');
        }

        Role::whereIn('id', $ids)->delete();

        return to_route('admin.roles.index')
            ->with('success', count($ids) . ' roles deleted successfully.');
    }
}
