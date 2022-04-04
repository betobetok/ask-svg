<?php

declare(strict_types=1);

namespace Tests\AskSvgTests;

use ASK\Svg\BladeIconsServiceProvider;
use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    use InteractsWithViews;

    protected function getBasePath()
    {
        return __DIR__ . '/laravel';
    }

    protected function getPackageProviders($app): array
    {
        return [BladeIconsServiceProvider::class];
    }
}
