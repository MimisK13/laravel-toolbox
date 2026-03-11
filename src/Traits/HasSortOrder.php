<?php

declare(strict_types=1);

namespace Mimisk\LaravelToolbox\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasSortOrder
{
    public static function bootHasSortOrder(): void
    {
        static::creating(function ($model): void {
            $column = method_exists($model, 'getSortOrderColumn') ? $model->getSortOrderColumn() : 'sort_order';

            if ($model->{$column} !== null) {
                return;
            }

            $model->{$column} = (int) static::query()->max($column) + 1;
        });
    }

    public function scopeOrdered(Builder $query, string $direction = 'asc'): Builder
    {
        $column = method_exists($this, 'getSortOrderColumn') ? $this->getSortOrderColumn() : 'sort_order';

        return $query->orderBy($column, $direction);
    }
}
