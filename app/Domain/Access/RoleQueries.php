<?php

declare(strict_types=1);

namespace App\Domain\Access;

use App\Domain\Access\Models\Role;
use App\Support\BaseQueries;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class RoleQueries extends BaseQueries
{
    public function getBuilder(array $filterData): Builder
    {
        return Role::query()
            ->when($filterData['search_text'] ?? null, function ($query) use ($filterData): void {
                $query->where(function (\Illuminate\Contracts\Database\Query\Builder $query) use ($filterData): void {
                    $query->where('name', 'like', '%' . $filterData['search_text'] . '%');
                });
            })
            ->when($filterData['sort_by'] ?? null, function ($query) use ($filterData): void {
                $query->orderBy($filterData['sort_by'], $filterData['sort_direction'] ?? 'asc');
            }, function ($query): void {
                $query->orderBy('name', 'asc');
            });
    }

    public function listQuery(array $filterData): LengthAwarePaginator
    {
        return $this->getBuilder($filterData)
            ->paginate($filterData['per_page'] ?? 10)
            ->withQueryString();
    }
}
