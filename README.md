# toolbox

[![Tests](https://github.com/MimisK13/laravel-toolbox/actions/workflows/tests.yml/badge.svg)](https://github.com/MimisK13/laravel-toolbox/actions/workflows/tests.yml)
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

Minimal Laravel 12/13 package scaffold for reusable toolbox utilities.

## Requirements

- PHP 8.4+
- Laravel 12 or 13

## Installation

```bash
composer require mimisk/laravel-toolbox
```

Laravel auto-discovers the package service provider.

## Trait Columns

| Trait | Required Columns (default) | Custom method |
| --- | --- | --- |
| `HasSlug` | `slug` + source column `title` | `getSlugColumn()`, `getSlugSourceColumn()` |
| `HasUuid` | `uuid` | `getUuidColumn()` |
| `HasUlid` | `ulid` | `getUlidColumn()` |
| `HasActiveFlag` | `is_active` (boolean) | `getActiveFlagColumn()` |
| `HasPublishedState` | `published_at` (datetime/timestamp nullable) | `getPublishedAtColumn()` |
| `HasArchivedState` | `archived_at` (datetime/timestamp nullable) | `getArchivedAtColumn()` |
| `HasMetaData` | `metadata` (json nullable) | `getMetaDataColumn()` |
| `HasSortOrder` | `sort_order` (integer nullable) | `getSortOrderColumn()` |

## Included Traits

### `HasSlug`
Automatically generates a slug on model creation from a source attribute (default source: `title`, target column: `slug`).

```php
use Mimisk\LaravelToolbox\Traits\HasSlug;

class Post extends Model
{
    use HasSlug;

    protected function getSlugSourceColumn(): string
    {
        return 'name';
    }
}
```

### `HasUuid`
Automatically sets a UUID on model creation (default column: `uuid`).

```php
use Mimisk\LaravelToolbox\Traits\HasUuid;

class Invoice extends Model
{
    use HasUuid;
}
```

### `HasUlid`
Automatically sets a ULID on model creation (default column: `ulid`).

```php
use Mimisk\LaravelToolbox\Traits\HasUlid;

class Ticket extends Model
{
    use HasUlid;
}
```

### `HasActiveFlag`
Adds active/inactive scopes and helpers for boolean flags (default column: `is_active`).

```php
use Mimisk\LaravelToolbox\Traits\HasActiveFlag;

class Stage extends Model
{
    use HasActiveFlag;
}

$activeStages = Stage::active()->get();
$stage->deactivate();
```

### `HasPublishedState`
Adds published/unpublished scopes and helpers using a datetime field (default column: `published_at`).

```php
use Mimisk\LaravelToolbox\Traits\HasPublishedState;

class Article extends Model
{
    use HasPublishedState;
}

$published = Article::published()->get();
$article->markAsPublished();
```

### `HasArchivedState`
Adds archived/unarchived scopes and helpers using a datetime field (default column: `archived_at`).

```php
use Mimisk\LaravelToolbox\Traits\HasArchivedState;

class Event extends Model
{
    use HasArchivedState;
}

$archived = Event::archived()->get();
$event->markAsArchived();
```

### `HasMetaData`
Adds helpers for structured metadata access via an array-cast attribute (default column: `metadata`).

```php
use Mimisk\LaravelToolbox\Traits\HasMetaData;

class Product extends Model
{
    use HasMetaData;
}

$product->setMeta('shipping.weight', 2.4)->save();
$weight = $product->getMeta('shipping.weight');
```

### `HasSortOrder`
Automatically sets incremental sort order on create and provides an ordered scope (default column: `sort_order`).

```php
use Mimisk\LaravelToolbox\Traits\HasSortOrder;

class MenuItem extends Model
{
    use HasSortOrder;
}

$items = MenuItem::ordered()->get();
```

## Testing

```bash
composer test
```

## Static Analysis

```bash
composer analyse
```

[ico-version]: https://img.shields.io/packagist/v/mimisk/laravel-toolbox.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/mimisk/laravel-toolbox.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/mimisk/laravel-toolbox
[link-downloads]: https://packagist.org/packages/mimisk/laravel-toolbox
