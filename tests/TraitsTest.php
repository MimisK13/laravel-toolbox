<?php

declare(strict_types=1);

namespace Mimisk\LaravelToolbox\Tests;

use Illuminate\Database\Eloquent\Model;
use Mimisk\LaravelToolbox\Traits\HasActiveFlag;
use Mimisk\LaravelToolbox\Traits\HasMetaData;
use Mimisk\LaravelToolbox\Traits\HasPublishedState;
use Mimisk\LaravelToolbox\Traits\HasSlug;
use Mimisk\LaravelToolbox\Traits\HasUuid;

class TraitsTest extends TestCase
{
    public function test_model_traits_apply_expected_defaults_and_helpers(): void
    {
        $post = TestPost::query()->create(['title' => 'My First Post']);

        $this->assertNotEmpty($post->uuid);
        $this->assertSame('my-first-post', $post->slug);
        $this->assertTrue($post->is_active);

        $this->assertFalse($post->isPublished());
        $post->markAsPublished();
        $this->assertTrue($post->fresh()->isPublished());

        $post->setMeta('seo.description', 'hello');
        $this->assertTrue($post->hasMeta('seo.description'));
        $this->assertSame('hello', $post->getMeta('seo.description'));

        $post->forgetMeta('seo.description');
        $this->assertFalse($post->hasMeta('seo.description'));
    }

    public function test_active_and_published_scopes(): void
    {
        $active = TestPost::query()->create(['title' => 'Active']);
        $inactive = TestPost::query()->create(['title' => 'Inactive', 'is_active' => false]);
        $published = TestPost::query()->create(['title' => 'Published', 'published_at' => now()]);

        $this->assertSame([$active->id, $published->id], TestPost::query()->active()->pluck('id')->all());
        $this->assertSame([$inactive->id], TestPost::query()->inactive()->pluck('id')->all());
        $this->assertSame([$published->id], TestPost::query()->published()->pluck('id')->all());
    }
}

class TestPost extends Model
{
    use HasActiveFlag;
    use HasMetaData;
    use HasPublishedState;
    use HasSlug;
    use HasUuid;

    protected $table = 'test_posts';

    protected $guarded = [];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
