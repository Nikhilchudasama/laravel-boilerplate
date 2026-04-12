<?php

declare(strict_types=1);

namespace App\Domain\Admin\Http\Controllers;

use App\Domain\Users\Models\User;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total_users' => User::count(),
                'active_users' => User::where('active', true)->count(),
                'total_roles' => Role::count(),
            ],
            'recentActivities' => Activity::with('causer')
                ->latest()
                ->take(5)
                ->get()
                ->map(function (Activity $activity): array {
                    /** @var User|null $causer */
                    $causer = $activity->causer;

                    return [
                        'id' => $activity->id,
                        'description' => $activity->description,
                        'subject_type' => $activity->subject_type,
                        'causer_name' => $causer ? $causer->name : 'System',
                        'created_at' => $activity->created_at?->diffForHumans(),
                    ];
                }),
        ]);
    }
}
