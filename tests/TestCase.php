<?php

declare(strict_types=1);

namespace Mimisk\LaravelToolbox\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mimisk\LaravelToolbox\ToolboxServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function getPackageProviders($app): array
    {
        return [
            ToolboxServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('test_posts', function (Blueprint $table): void {
            $table->id();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->uuid('uuid')->nullable();
            $table->string('ulid', 26)->nullable();
            $table->boolean('is_active')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->unsignedInteger('sort_order')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('test_custom_posts', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->nullable();
            $table->string('url_key')->nullable();
            $table->uuid('public_id')->nullable();
            $table->string('public_ulid', 26)->nullable();
            $table->boolean('enabled')->nullable();
            $table->timestamp('live_at')->nullable();
            $table->timestamp('retired_at')->nullable();
            $table->unsignedInteger('position')->nullable();
            $table->json('meta_payload')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('test_posts');
        Schema::dropIfExists('test_custom_posts');

        parent::tearDown();
    }
}
