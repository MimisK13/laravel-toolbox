# mimisk/laravel-toolbox

[![Tests](https://github.com/MimisK13/laravel-toolbox/actions/workflows/tests.yml/badge.svg)](https://github.com/MimisK13/laravel-toolbox/actions/workflows/tests.yml)
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

Minimal Laravel 12 package scaffold for reusable toolbox utilities.

## Requirements

- PHP 8.4+
- Laravel 12+

## Installation

```bash
composer require mimisk/laravel-toolbox
```

Laravel auto-discovers the package service provider.

## Included Traits

### `HasUuid`
Automatically sets a UUID on model creation (default column: `uuid`).

### `HasSlug`
Automatically generates a slug on model creation from a source attribute (default source: `title`, target column: `slug`).

### `HasActiveFlag`
Adds active/inactive scopes and helpers for boolean flags (default column: `is_active`).

### `HasPublishedState`
Adds published/unpublished scopes and helpers using a datetime field (default column: `published_at`).

### `HasMetaData`
Adds helpers for structured metadata access via an array-cast attribute (default column: `metadata`).

## Testing

```bash
composer test
```

[ico-version]: https://img.shields.io/packagist/v/mimisk/laravel-toolbox.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/mimisk/laravel-toolbox.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/mimisk/laravel-toolbox
[link-downloads]: https://packagist.org/packages/mimisk/laravel-toolbox
