<?php

declare(strict_types=1);

namespace Tests\AskSvgTests;

use ASK\Svg\BladeIconsServiceProvider;
use ASK\Svg\Factory;
use ASK\Svg\IconsManifest;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    use InteractsWithViews;

    protected function prepareSets(array $config = [], array $setOptions = []): Factory
    {
        $manifest = new IconsManifest(new Filesystem(), __DIR__ . '/fixtures/blade-icons.php');

        $factory = new Factory(
            new Filesystem(),
            $manifest,
            $this->app->make(FilesystemFactory::class),
            $config,
        );

        return $this->app->instance(Factory::class, $factory);
    }

    protected function getPackageProviders($app): array
    {
        return [BladeIconsServiceProvider::class];
    }
}
