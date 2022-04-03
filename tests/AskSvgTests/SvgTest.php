<?php

namespace Tests\AskSvgTests;

use ASK\Svg\BladeIconsServiceProvider;
use ASK\Svg\Factory;
use ASK\Svg\IconsManifest;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;

class SvgTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        $this->manifestPath = __DIR__ . '/fixtures/blade-icons.php';
        @unlink($this->manifestPath);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        @unlink($this->manifestPath);
    }

    public function testDefaultConfigFile()
    {
        $this->prepareSets([]);
        $config = include __DIR__ . '/../../config/blade-icons.php';
        $this->assertArrayHasKey('sets', $config);
        $sets = $config['sets'];
        $this->assertArrayHasKey('default', $sets);
        foreach ($sets as $set) {
            $this->assertArrayHasKey('path', $set);
            $this->assertArrayHasKey('prefix', $set);
        }

        $manifest = new IconsManifest(new Filesystem(), $this->manifestPath);

        $factory = new Factory(
            new Filesystem(),
            $manifest,
            $this->app->make(FilesystemFactory::class),
        );

        $factory->registerComponents();
        dump($factory->all());
        $this->assertClassHasAttribute('sets', Factory::class);
    }
}
