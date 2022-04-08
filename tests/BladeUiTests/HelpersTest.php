<?php

declare(strict_types=1);

namespace Tests\BladeUiTests;

use ASK\Svg\Svg;

class HelpersTest extends TestCase
{
    /** @test */
    public function the_svg_helper_returns_an_svg_instance()
    {
        $this->prepareSets();

        $svg = svg('camera');

        $this->assertInstanceOf(Svg::class, $svg);
    }
}
