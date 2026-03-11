<?php

declare(strict_types=1);

namespace Mimisk\LaravelToolbox\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

trait HasPublishedState
{
    public function scopePublished(Builder $query): Builder
    {
        $column = method_exists($this, 'getPublishedAtColumn') ? $this->getPublishedAtColumn() : 'published_at';

        return $query->whereNotNull($column);
    }

    public function scopeUnpublished(Builder $query): Builder
    {
        $column = method_exists($this, 'getPublishedAtColumn') ? $this->getPublishedAtColumn() : 'published_at';

        return $query->whereNull($column);
    }

    public function markAsPublished(?Carbon $publishedAt = null): bool
    {
        $column = method_exists($this, 'getPublishedAtColumn') ? $this->getPublishedAtColumn() : 'published_at';
        $this->{$column} = $publishedAt ?? Carbon::now();

        return $this->save();
    }

    public function markAsUnpublished(): bool
    {
        $column = method_exists($this, 'getPublishedAtColumn') ? $this->getPublishedAtColumn() : 'published_at';
        $this->{$column} = null;

        return $this->save();
    }

    public function isPublished(): bool
    {
        $column = method_exists($this, 'getPublishedAtColumn') ? $this->getPublishedAtColumn() : 'published_at';

        return $this->{$column} !== null;
    }
}
