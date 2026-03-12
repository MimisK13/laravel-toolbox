<?php

declare(strict_types=1);

namespace Mimisk\LaravelToolbox\Traits;

use Illuminate\Support\Arr;

trait HasMetaData
{
    public function initializeHasMetaData(): void
    {
        $this->mergeCasts([
            $this->getMetaDataColumnName() => 'array',
        ]);
    }

    public function getMeta(string $key, mixed $default = null): mixed
    {
        return data_get($this->getMetaDataPayload(), $key, $default);
    }

    public function setMeta(string $key, mixed $value): self
    {
        $metadata = $this->getMetaDataPayload();
        data_set($metadata, $key, $value);

        $this->{$this->getMetaDataColumnName()} = $metadata;

        return $this;
    }

    public function hasMeta(string $key): bool
    {
        return Arr::has($this->getMetaDataPayload(), $key);
    }

    public function forgetMeta(string $key): self
    {
        $metadata = $this->getMetaDataPayload();
        data_forget($metadata, $key);

        $this->{$this->getMetaDataColumnName()} = $metadata;

        return $this;
    }

    protected function getMetaDataPayload(): array
    {
        $payload = $this->{$this->getMetaDataColumnName()} ?? [];

        return is_array($payload) ? $payload : [];
    }

    protected function getMetaDataColumnName(): string
    {
        return method_exists($this, 'getMetaDataColumn') ? $this->getMetaDataColumn() : 'metadata';
    }
}
