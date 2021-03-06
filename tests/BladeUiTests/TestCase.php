<?php

declare(strict_types=1);

namespace Tests\BladeUiTests;

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

    protected function getBasePath()
    {
        return __DIR__ . '/../AskSvgTests/laravel';
    }

    protected function prepareSets(array $config = [], array $setOptions = []): Factory
    {
        $factory = new Factory(
            new Filesystem(),
            $this->app->make(IconsManifest::class),
            $this->app->make(FilesystemFactory::class),
            $config,
        );

        $factory = $factory
            ->add('default', array_merge([
                'path' => __DIR__ . '/resources/svg',
                'prefix' => 'icon',
            ], $setOptions['default'] ?? []))
            ->add('zondicons', array_merge([
                'path' => __DIR__ . '/resources/zondicons',
                'prefix' => 'zondicon',
            ], $setOptions['zondicons'] ?? []));

        return $this->app->instance(Factory::class, $factory);
    }

    protected function getPackageProviders($app): array
    {
        return [BladeIconsServiceProvider::class];
    }
}
