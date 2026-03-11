<?php

declare(strict_types=1);

namespace Mimisk\LaravelToolbox\Tests;

use Mimisk\LaravelToolbox\ToolboxServiceProvider;

class ServiceProviderTest extends TestCase
{
    public function test_service_provider_is_loaded(): void
    {
        $this->assertTrue($this->app->providerIsLoaded(ToolboxServiceProvider::class));
        $this->assertTrue($this->app['config']->get('toolbox.enabled'));
    }
}
