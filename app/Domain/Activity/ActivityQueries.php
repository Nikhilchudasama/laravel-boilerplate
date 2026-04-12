<?php

declare(strict_types=1);

namespace App\Domain\Activity;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Activitylog\Models\Activity;

class ActivityQueries
{
    public function listQuery(array $filterData): LengthAwarePaginator
    {
        return Activity::with(['causer', 'subject'])
            ->when($filterData['search_text'] ?? null, function ($query, $searchText): void {
                $query->where(function (Builder $q) use ($searchText): void {
                    $q->where('description', 'like', sprintf('%%%s%%', $searchText))
                        ->orWhere('log_name', 'like', sprintf('%%%s%%', $searchText))
                        ->orWhereHas('causer', function (Builder $q) use ($searchText): void {
                            $q->where('name', 'like', sprintf('%%%s%%', $searchText));
                        });
                });
            })
            ->when($filterData['log_name'] ?? null, function ($query, $logName): void {
                $query->where('log_name', $logName);
            })
            ->when($filterData['causer_id'] ?? null, function ($query, $causerId): void {
                $query->where('causer_id', $causerId);
            })
            ->when($filterData['date_from'] ?? null, function ($query, $dateFrom): void {
                $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($filterData['date_to'] ?? null, function ($query, $dateTo): void {
                $query->whereDate('created_at', '<=', $dateTo);
            })
            ->when($filterData['sort_by'] ?? null, function ($query, $sortBy) use ($filterData): void {
                $query->orderBy($sortBy, $filterData['sort_direction'] ?? 'desc');
            }, function ($query): void {
                $query->latest();
            })
            ->paginate($filterData['per_page'] ?? 10)
            ->withQueryString();
    }
}
