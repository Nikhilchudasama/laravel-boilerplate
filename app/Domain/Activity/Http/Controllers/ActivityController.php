<?php

declare(strict_types=1);

namespace App\Domain\Activity\Http\Controllers;

use App\Domain\Activity\ActivityQueries;
use App\Domain\Users\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    public function __construct(
        protected ActivityQueries $queries
    ) {}

    public function index(Request $request)
    {
        $this->authorize('view_activity_logs');

        $filterData = [
            'search_text' => $request->get('search_text'),
            'log_name' => $request->get('log_name'),
            'causer_id' => $request->get('causer_id'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'sort_by' => $request->get('sort_by'),
            'sort_direction' => $request->get('sort_direction'),
            'per_page' => $request->get('per_page'),
        ];

        $activities = $this->queries->listQuery($filterData);

        if ($request->wantsJson()) {
            return [
                'total_records' => $activities->total(),
                'data' => $activities->getCollection()->map(fn ($item): array => [
                    'id' => $item->id,
                    'log_name' => $item->log_name,
                    'description' => $item->description,
                    'causer' => $item->causer ? $item->causer->name : 'System',
                    'subject_type' => basename($item->subject_type),
                    'created_at' => $item->created_at->toDateTimeString(),
                    'properties' => $item->properties,
                ]),
            ];
        }

        return Inertia::render('Admin/ActivityLog/Index', [
            'activities' => $activities,
            'logNames' => Activity::select('log_name')->distinct()->pluck('log_name'),
            'causers' => User::select('id', 'name')->get(),
        ]);
    }
}
