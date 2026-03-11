# mimisk/laravel-toolbox

Minimal Laravel 12 package scaffold for reusable toolbox utilities.

## Requirements

- PHP 8.4+
- Laravel 12+

## Installation

```bash
composer require mimisk/laravel-toolbox
```

The package service provider is auto-discovered by Laravel.

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
