<?php

declare(strict_types=1);

namespace App\Domain\Access\Exports;

use App\Domain\Access\RoleQueries;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RolesExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(
        protected RoleQueries $roleQueries,
        protected array $filterData
    ) {}

    public function query(): Builder
    {
        return $this->roleQueries->getBuilder($this->filterData);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Created At',
        ];
    }

    public function map($role): array
    {
        return [
            $role->id,
            $role->name,
            $role->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
