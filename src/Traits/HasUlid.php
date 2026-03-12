<?php

declare(strict_types=1);

namespace Mimisk\LaravelToolbox\Traits;

use Illuminate\Support\Str;

trait HasUlid
{
    public static function bootHasUlid(): void
    {
        static::creating(function ($model): void {
            $column = method_exists($model, 'getUlidColumn') ? $model->getUlidColumn() : 'ulid';

            if (! $model->{$column}) {
                $model->{$column} = (string) Str::ulid();
            }
        });
    }
}
