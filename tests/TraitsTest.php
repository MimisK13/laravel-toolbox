<?php

declare(strict_types=1);

namespace Mimisk\LaravelToolbox\Tests;

use Illuminate\Database\Eloquent\Model;
use Mimisk\LaravelToolbox\Traits\HasActiveFlag;
use Mimisk\LaravelToolbox\Traits\HasArchivedState;
use Mimisk\LaravelToolbox\Traits\HasMetaData;
use Mimisk\LaravelToolbox\Traits\HasPublishedState;
use Mimisk\LaravelToolbox\Traits\HasSlug;
use Mimisk\LaravelToolbox\Traits\HasSortOrder;
use Mimisk\LaravelToolbox\Traits\HasUlid;
use Mimisk\LaravelToolbox\Traits\HasUuid;

class TraitsTest extends TestCase
{
    public function test_model_traits_apply_expected_defaults_and_helpers(): void
    {
        $post = TestPost::query()->create(['title' => 'My First Post']);

        $this->assertNotEmpty($post->uuid);
        $this->assertNotEmpty($post->ulid);
        $this->assertSame('my-first-post', $post->slug);
        $this->assertTrue($post->is_active);
        $this->assertSame(1, $post->sort_order);

        $this->assertFalse($post->isPublished());
        $post->markAsPublished();
        $this->assertTrue($post->fresh()->isPublished());
        $post->markAsUnpublished();
        $this->assertFalse($post->fresh()->isPublished());

        $this->assertFalse($post->isArchived());
        $post->markAsArchived();
        $this->assertTrue($post->fresh()->isArchived());
        $post->markAsUnarchived();
        $this->assertFalse($post->fresh()->isArchived());

        $post->setMeta('seo.description', 'hello');
        $post->setMeta('seo.nullable', null);

        $this->assertTrue($post->hasMeta('seo.description'));
        $this->assertSame('hello', $post->getMeta('seo.description'));
        $this->assertTrue($post->hasMeta('seo.nullable'));

        $post->forgetMeta('seo.description');
        $this->assertFalse($post->hasMeta('seo.description'));
    }

    public function test_active_and_published_scopes(): void
    {
        $active = TestPost::query()->create(['title' => 'Active']);
        $inactive = TestPost::query()->create(['title' => 'Inactive', 'is_active' => false]);
        $published = TestPost::query()->create(['title' => 'Published', 'published_at' => now(), 'archived_at' => now()]);

        $activeIds = TestPost::query()->active()->pluck('id')->all();
        sort($activeIds);

        $expectedActiveIds = [$active->id, $published->id];
        sort($expectedActiveIds);

        $this->assertSame($expectedActiveIds, $activeIds);
        $this->assertSame([$inactive->id], TestPost::query()->inactive()->pluck('id')->all());
        $this->assertSame([$published->id], TestPost::query()->published()->pluck('id')->all());
        $unpublishedIds = TestPost::query()->unpublished()->pluck('id')->all();
        sort($unpublishedIds);
        $expectedUnpublishedIds = [$active->id, $inactive->id];
        sort($expectedUnpublishedIds);
        $this->assertSame($expectedUnpublishedIds, $unpublishedIds);
        $this->assertSame([$published->id], TestPost::query()->archived()->pluck('id')->all());
        $unarchivedIds = TestPost::query()->unarchived()->pluck('id')->all();
        sort($unarchivedIds);
        $expectedUnarchivedIds = [$active->id, $inactive->id];
        sort($expectedUnarchivedIds);
        $this->assertSame($expectedUnarchivedIds, $unarchivedIds);
    }

    public function test_active_helpers_and_ordered_scope(): void
    {
        $postA = TestPost::query()->create(['title' => 'A']);
        $postB = TestPost::query()->create(['title' => 'B']);
        $postC = TestPost::query()->create(['title' => 'C', 'sort_order' => 10]);

        $this->assertSame([1, 2, 10], TestPost::query()->ordered()->pluck('sort_order')->all());
        $this->assertSame([10, 2, 1], TestPost::query()->ordered('desc')->pluck('sort_order')->all());

        $this->assertTrue($postA->deactivate());
        $this->assertFalse((bool) $postA->fresh()->is_active);

        $this->assertTrue($postB->activate());
        $this->assertTrue((bool) $postB->fresh()->is_active);

        $this->assertSame(10, $postC->sort_order);
    }

    public function test_slug_is_not_overwritten_when_provided(): void
    {
        $post = TestPost::query()->create([
            'title' => 'Ignored Title',
            'slug' => 'my-custom-slug',
        ]);

        $this->assertSame('my-custom-slug', $post->slug);
    }

    public function test_custom_trait_columns_are_supported(): void
    {
        $postA = TestCustomPost::query()->create(['name' => 'Custom Alpha']);
        $postB = TestCustomPost::query()->create(['name' => 'Custom Beta']);

        $this->assertSame('custom-alpha', $postA->url_key);
        $this->assertNotEmpty($postA->public_id);
        $this->assertNotEmpty($postA->public_ulid);
        $this->assertTrue((bool) $postA->enabled);
        $this->assertSame(1, $postA->position);
        $this->assertSame(2, $postB->position);

        $this->assertFalse($postA->isPublished());
        $this->assertFalse($postA->isArchived());

        $postA->markAsPublished();
        $postA->markAsArchived();

        $this->assertTrue($postA->fresh()->isPublished());
        $this->assertTrue($postA->fresh()->isArchived());

        $postA->setMeta('flags.featured', true)->save();
        $this->assertTrue($postA->fresh()->hasMeta('flags.featured'));
        $this->assertTrue((bool) $postA->fresh()->getMeta('flags.featured'));

        $postA->deactivate();
        $this->assertFalse((bool) $postA->fresh()->enabled);

        $activeIds = TestCustomPost::query()->active()->pluck('id')->all();
        $this->assertSame([$postB->id], $activeIds);

        $publishedIds = TestCustomPost::query()->published()->pluck('id')->all();
        $this->assertSame([$postA->id], $publishedIds);

        $archivedIds = TestCustomPost::query()->archived()->pluck('id')->all();
        $this->assertSame([$postA->id], $archivedIds);

        $this->assertSame(
            [$postA->position, $postB->position],
            TestCustomPost::query()->ordered()->pluck('position')->all()
        );
    }
}

class TestPost extends Model
{
    use HasActiveFlag;
    use HasArchivedState;
    use HasMetaData;
    use HasPublishedState;
    use HasSlug;
    use HasSortOrder;
    use HasUlid;
    use HasUuid;

    protected $table = 'test_posts';

    protected $guarded = [];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}

class TestCustomPost extends Model
{
    use HasActiveFlag;
    use HasArchivedState;
    use HasMetaData;
    use HasPublishedState;
    use HasSlug;
    use HasSortOrder;
    use HasUlid;
    use HasUuid;

    protected $table = 'test_custom_posts';

    protected $guarded = [];

    protected $casts = [
        'live_at' => 'datetime',
        'retired_at' => 'datetime',
    ];

    protected function getSlugColumn(): string
    {
        return 'url_key';
    }

    protected function getSlugSourceColumn(): string
    {
        return 'name';
    }

    protected function getUuidColumn(): string
    {
        return 'public_id';
    }

    protected function getUlidColumn(): string
    {
        return 'public_ulid';
    }

    protected function getActiveFlagColumn(): string
    {
        return 'enabled';
    }

    protected function getPublishedAtColumn(): string
    {
        return 'live_at';
    }

    protected function getArchivedAtColumn(): string
    {
        return 'retired_at';
    }

    protected function getMetaDataColumn(): string
    {
        return 'meta_payload';
    }

    protected function getSortOrderColumn(): string
    {
        return 'position';
    }
}
