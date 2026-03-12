<?php

declare(strict_types=1);

namespace Mimisk\LaravelToolbox\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
    public static function bootHasUuid(): void
    {
        static::creating(function ($model): void {
            $column = method_exists($model, 'getUuidColumn') ? $model->getUuidColumn() : 'uuid';

            if (! $model->{$column}) {
                $model->{$column} = (string) Str::uuid();
            }
        });
    }
}
