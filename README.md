# mimisk/laravel-toolbox

[![Tests](https://github.com/MimisK13/laravel-toolbox/actions/workflows/tests.yml/badge.svg)](https://github.com/MimisK13/laravel-toolbox/actions/workflows/tests.yml)

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
=======
`ToolboxServiceProvider` is auto-discovered by Laravel.

If you want to publish package config:

```bash
php artisan vendor:publish --tag=toolbox-config
```

## Usage

### 1) Add traits to your model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mimisk\LaravelToolbox\Traits\HasActiveFlag;
use Mimisk\LaravelToolbox\Traits\HasArchivedState;
use Mimisk\LaravelToolbox\Traits\HasMetaData;
use Mimisk\LaravelToolbox\Traits\HasPublishedState;
use Mimisk\LaravelToolbox\Traits\HasSlug;
use Mimisk\LaravelToolbox\Traits\HasSortOrder;
use Mimisk\LaravelToolbox\Traits\HasUlid;
use Mimisk\LaravelToolbox\Traits\HasUuid;

class Post extends Model
{
    use HasActiveFlag;
    use HasArchivedState;
    use HasMetaData;
    use HasPublishedState;
    use HasSlug;
    use HasSortOrder;
    use HasUlid;
    use HasUuid;
}
```

### 2) Ensure migration columns exist

```php
$table->uuid('uuid')->nullable();
$table->string('ulid')->nullable();
$table->string('slug')->nullable();
$table->boolean('is_active')->default(true);
$table->timestamp('published_at')->nullable();
$table->timestamp('archived_at')->nullable();
$table->integer('sort_order')->nullable();
$table->json('metadata')->nullable();
```

## Included Traits

- `HasUuid`: auto-generates a UUID on creating (`uuid`).
- `HasUlid`: auto-generates a ULID on creating (`ulid`).
- `HasSlug`: auto-generates a slug from `title` (`slug`).
- `HasActiveFlag`: active/inactive scopes and activate/deactivate helpers (`is_active`).
- `HasPublishedState`: published/unpublished scopes and publish helpers (`published_at`).
- `HasArchivedState`: archived/unarchived scopes and archive helpers (`archived_at`).
- `HasSortOrder`: auto-assigns incremental order and adds `ordered()` scope (`sort_order`).
- `HasMetaData`: metadata `get/set/has/forget` helpers (`metadata`).

Most traits support custom column methods (e.g. `getSlugColumn()`, `getUuidColumn()`, etc.) if you want to override defaults.

## Testing

```bash
composer test
```
CI runs on every push/PR through GitHub Actions (`.github/workflows/tests.yml`).
