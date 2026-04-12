<?php

declare(strict_types=1);

namespace App\Support\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasGlobalSearch
{
    protected function scopeGlobalSearch(Builder $query, string $term): Builder
    {
        $columns = property_exists($this, 'searchable') ? $this->searchable : [];

        if (empty($columns)) {
            return $query;
        }

        return $query->where(function (\Illuminate\Contracts\Database\Query\Builder $query) use ($term, $columns): void {
            foreach ($columns as $column) {
                $query->orWhere($column, 'like', sprintf('%%%s%%', $term));
            }
        });
    }
}
