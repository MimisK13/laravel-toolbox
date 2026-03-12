<?php

declare(strict_types=1);

namespace Mimisk\LaravelToolbox\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasActiveFlag
{
    public static function bootHasActiveFlag(): void
    {
        static::creating(function ($model): void {
            $column = method_exists($model, 'getActiveFlagColumn') ? $model->getActiveFlagColumn() : 'is_active';

            if ($model->{$column} === null) {
                $model->{$column} = true;
            }
        });
    }

    public function scopeActive(Builder $query): Builder
    {
        $column = method_exists($this, 'getActiveFlagColumn') ? $this->getActiveFlagColumn() : 'is_active';

        return $query->where($column, true);
    }

    public function scopeInactive(Builder $query): Builder
    {
        $column = method_exists($this, 'getActiveFlagColumn') ? $this->getActiveFlagColumn() : 'is_active';

        return $query->where($column, false);
    }

    public function activate(): bool
    {
        $column = method_exists($this, 'getActiveFlagColumn') ? $this->getActiveFlagColumn() : 'is_active';
        $this->{$column} = true;

        return $this->save();
    }

    public function deactivate(): bool
    {
        $column = method_exists($this, 'getActiveFlagColumn') ? $this->getActiveFlagColumn() : 'is_active';
        $this->{$column} = false;

        return $this->save();
    }
}
