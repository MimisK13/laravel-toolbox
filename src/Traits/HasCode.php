<?php

declare(strict_types=1);

namespace Mimisk\LaravelToolbox\Traits;

trait HasCode
{
    public static function bootHasCode(): void
    {
        static::creating(function ($model): void {
            $model->generateCode();
        });
    }

    public function generateCode(): void
    {
        $codeColumn = method_exists($this, 'getCodeColumn') ? $this->getCodeColumn() : 'code';

        if (! empty($this->{$codeColumn})) {
            return;
        }

        $prefix = method_exists($this, 'getCodePrefix') ? (string) $this->getCodePrefix() : '';
        $separator = method_exists($this, 'getCodeSeparator') ? (string) $this->getCodeSeparator() : '-';
        $padding = method_exists($this, 'getCodePadding') ? (int) $this->getCodePadding() : 6;
        $startNumber = method_exists($this, 'getCodeStartNumber') ? (int) $this->getCodeStartNumber() : 1;

        $nextNumber = $this->resolveNextCodeNumber($codeColumn, $prefix, $startNumber);
        $numericPart = $padding > 0
            ? str_pad((string) $nextNumber, $padding, '0', STR_PAD_LEFT)
            : (string) $nextNumber;

        if ($prefix === '') {
            $this->{$codeColumn} = $numericPart;

            return;
        }

        $this->{$codeColumn} = $prefix.$separator.$numericPart;
    }

    protected function resolveNextCodeNumber(string $codeColumn, string $prefix, int $startNumber): int
    {
        $existingCodes = static::query()
            ->where($codeColumn, 'like', $prefix.'%')
            ->pluck($codeColumn);

        $highestNumber = $startNumber - 1;

        foreach ($existingCodes as $existingCode) {
            if (! is_string($existingCode)) {
                continue;
            }

            if (preg_match('/(\d+)$/', $existingCode, $matches) !== 1) {
                continue;
            }

            $highestNumber = max($highestNumber, (int) $matches[1]);
        }

        return $highestNumber + 1;
    }
}
