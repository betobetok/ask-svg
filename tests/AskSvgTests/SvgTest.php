<?php

namespace Tests\AskSvgTests;

use ASK\Svg\BladeIconsServiceProvider;
use ASK\Svg\Factory;
use ASK\Svg\IconsManifest;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;

class SvgTest extends TestCase
{
    public function testDefaultConfigFile()
    {
        $this->prepareSets([]);
        $config = include __DIR__ . '/../config/blade-icons.php';
        $this->assertArrayHasKey('sets', $config);
        $sets = $config['sets'];
        $this->assertArrayHasKey('default', $sets);
        foreach ($sets as $set) {
            $this->assertArrayHasKey('path', $set);
            $this->assertArrayHasKey('prefix', $set);
        }
        $factory = new Factory(
            new Filesystem(),
            $this->app->make(IconsManifest::class),
            $this->app->make(FilesystemFactory::class),
        );
        // $blade = new BladeIconsServiceProvider($this->app);
        // $blade->register();
        $factory->registerComponents();
        $this->assertClassHasAttribute('sets', Factory::class);
    }
}
