<?php

declare(strict_types=1);

namespace Mimisk\LaravelToolbox\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::creating(function ($model): void {
            $model->generateSlug();
        });
    }

    public function generateSlug(): void
    {
        $slugColumn = method_exists($this, 'getSlugColumn') ? $this->getSlugColumn() : 'slug';

        if (! empty($this->{$slugColumn})) {
            return;
        }

        $sourceColumn = method_exists($this, 'getSlugSourceColumn') ? $this->getSlugSourceColumn() : 'title';
        $separator = method_exists($this, 'getSlugSeparator') ? $this->getSlugSeparator() : '-';

        if (! empty($this->{$sourceColumn})) {
            $this->{$slugColumn} = Str::slug((string) $this->{$sourceColumn}, $separator);
        }
    }
}
