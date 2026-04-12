<?php

declare(strict_types=1);

namespace App\Domain\Users\Exports;

use App\Domain\Users\UserQueries;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(
        protected UserQueries $userQueries,
        protected array $filterData
    ) {}

    public function query(): Builder
    {
        return $this->userQueries->getBuilder($this->filterData);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Created At',
        ];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
