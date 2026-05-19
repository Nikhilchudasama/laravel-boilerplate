<?php

declare(strict_types=1);

namespace App\Domain\Users\Http\Controllers;

use App\Domain\Access\Models\Role;
use App\Domain\Users\Actions\CreateUserAction;
use App\Domain\Users\Actions\UpdateUserAction;
use App\Domain\Users\Data\CreateUserData;
use App\Domain\Users\Data\UpdateUserData;
use App\Domain\Users\Exports\UsersExport;
use App\Domain\Users\Models\User;
use App\Domain\Users\UserQueries;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function __construct(
        protected UserQueries $userQueries
    ) {}

    public function index(Request $request)
    {
        $this->authorize('view_users');

        $filterData = [
            'search_text' => $request->get('search_text'),
            'sort_by' => $request->get('sort_by'),
            'sort_direction' => $request->get('sort_direction'),
            'per_page' => $request->get('per_page'),
        ];

        $lengthAwarePaginator = $this->userQueries->listQuery($filterData);

        if ($request->wantsJson()) {
            return [
                'total_records' => $lengthAwarePaginator->total(),
                'data' => $lengthAwarePaginator->getCollection(),
            ];
        }

        return Inertia::render('Admin/Users/Index', [
            'users' => $lengthAwarePaginator,
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create_users');

        return Inertia::render('Admin/Users/UserForm', [
            'user' => null,
            'roles' => Role::all(),
        ]);
    }

    public function store(CreateUserData $data, CreateUserAction $action): RedirectResponse
    {
        $this->authorize('create_users');

        $action->execute($data);

        return to_route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function export(Request $request)
    {
        $filterData = [
            'search_text' => $request->get('search_text'),
            'sort_by' => $request->get('sort_by'),
            'sort_direction' => $request->get('sort_direction'),
        ];

        return Excel::download(
            new UsersExport($this->userQueries, $filterData),
            'users.xlsx'
        );
    }

    public function impersonate(User $user)
    {
        $this->authorize('edit_users');

        /** @var User $currentUser */
        $currentUser = Auth::user();

        if ($currentUser->id === $user->id) {
            return back()->with('error', 'You cannot impersonate yourself.');
        }

        if ($currentUser->canImpersonate() && $user->canBeImpersonated()) {
            $currentUser->impersonate($user);

            return to_route('admin.dashboard')->with('success', 'Impersonating ' . $user->name);
        }

        return back()->with('error', 'Permission denied for impersonation.');
    }

    public function leaveImpersonation()
    {
        Auth::user();

        if (resolve('impersonate')->isImpersonating()) {
            resolve('impersonate')->leave();

            return to_route('admin.users.index')->with('success', 'Returned to admin account');
        }

        return to_route('admin.dashboard');
    }

    public function edit(User $user): Response
    {
        $this->authorize('edit_users');

        return Inertia::render('Admin/Users/UserForm', [
            'user' => $user->load('roles'),
            'roles' => Role::all(),
        ]);
    }

    public function update(User $user, UpdateUserData $data, UpdateUserAction $action): RedirectResponse
    {
        $this->authorize('edit_users');

        $action->execute($user, $data);

        return to_route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function bulkDelete(Request $request): RedirectResponse
    {
        $this->authorize('delete_users');

        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return back()->with('error', 'No users selected.');
        }

        // Prevent deleting current user
        $filteredIds = array_filter($ids, fn ($id): bool => $id !== Auth::id());

        User::whereIn('id', $filteredIds)->delete();

        return to_route('admin.users.index')
            ->with('success', count($filteredIds) . ' users deleted successfully.');
    }

    public function bulkToggleActive(Request $request): RedirectResponse
    {
        $this->authorize('edit_users');

        $ids = $request->input('ids', []);
        $active = $request->boolean('active');

        if (empty($ids)) {
            return back()->with('error', 'No users selected.');
        }

        User::whereIn('id', $ids)->update(['active' => $active]);

        $status = $active ? 'activated' : 'deactivated';

        return to_route('admin.users.index')
            ->with('success', count($ids) . sprintf(' users %s successfully.', $status));
    }
}
