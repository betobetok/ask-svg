<?php

declare(strict_types=1);

namespace Tests;

use ASK\Svg\Factory;
use ASK\Svg\Svg;

class FilesystemDiskTest extends TestCase
{
    /** @test */
    public function it_can_retrieve_an_icon()
    {
        $factory = $this->prepareSets([], ['default' => [
            'disk' => 'external-disk',
            'path' => '/',
        ]]);

        $icon = $factory->svg('cake');

        $this->assertInstanceOf(Svg::class, $icon);
        $this->assertSame('cake', $icon->name());
    }

    /** @test */
    public function it_can_still_retrieve_an_icon_from_a_local_set()
    {
        $factory = $this->prepareSets([], ['default' => [
            'disk' => 'external-disk',
            'path' => '/',
        ]]);

        $icon = $factory->svg('zondicon-flag');

        $this->assertInstanceOf(Svg::class, $icon);
        $this->assertSame('flag', $icon->name());
    }

    /** @test */
    public function it_can_render_an_icon_component()
    {
        $this->prepareSets([], ['default' => [
            'disk' => 'external-disk',
            'path' => '/',
        ]]);

        $view = $this->blade('<x-icon-cake/>');

        $expected = <<<'HTML'
            <svg id="cake" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" ><path d="M 21 15.546 c -0.523 0 -1.046 0.151 -1.5 0.454 a 2.704 2.704 0 0 1 -3 0, 2.704 2.704 0 0 0 -3 0, 2.704 2.704 0 0 1 -3 0, 2.704 2.704 0 0 0 -3 0, 2.704 2.704 0 0 1 -3 0, 2.701 2.701 0 0 0 -1.5 -0.454 M 9 6 v 2 m 3 -2 v 2 m 3 -2 v 2 M 9 3 h 0.01 M 12 3 h 0.01 M 15 3 h 0.01 M 21 21 v -7 a 2 2 0 0 0 -2 -2 H 5 a 2 2 0 0 0 -2 2 v 7 h 18 z m -3 -9 v -2 a 2 2 0 0 0 -2 -2 H 8 a 2 2 0 0 0 -2 2 v 2 h 12 z "  stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
            HTML;

        $view->assertSee($expected, false);
    }

    /** @test */
    public function the_local_filesystem_is_used_for_the_disk_option_with_an_empty_string()
    {
        $factory = $this->prepareSets([], ['default' => [
            'disk' => '',
            'path' => $this->app->basePath('resources'),
        ]]);

        $this->assertInstanceOf(Factory::class, $factory);
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('filesystems.disks.external-disk', [
            'driver' => 'local',
            'root' => __DIR__ . '/resources/external-disk',
        ]);
    }
}
