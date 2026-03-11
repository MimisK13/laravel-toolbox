<?php

declare(strict_types=1);

namespace Mimisk\LaravelToolbox\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

trait HasArchivedState
{
    public function scopeArchived(Builder $query): Builder
    {
        return $query->whereNotNull($this->getArchivedAtColumnName());
    }

    public function scopeUnarchived(Builder $query): Builder
    {
        return $query->whereNull($this->getArchivedAtColumnName());
    }

    public function markAsArchived(?Carbon $archivedAt = null): bool
    {
        $this->{$this->getArchivedAtColumnName()} = $archivedAt ?? Carbon::now();

        return $this->save();
    }

    public function markAsUnarchived(): bool
    {
        $this->{$this->getArchivedAtColumnName()} = null;

        return $this->save();
    }

    public function isArchived(): bool
    {
        return $this->{$this->getArchivedAtColumnName()} !== null;
    }

    protected function getArchivedAtColumnName(): string
    {
        return method_exists($this, 'getArchivedAtColumn') ? $this->getArchivedAtColumn() : 'archived_at';
    }
}
