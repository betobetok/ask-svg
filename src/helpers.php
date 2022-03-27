<?php

declare(strict_types=1);

use ASK\Svg\Factory;
use ASK\Svg\Svg;

if (!function_exists('svg')) {
    function svg(string $name, $class = '', array $attributes = []): Svg
    {
        return app(Factory::class)->svg($name, $class, $attributes);
    }
}

if (!function_exists('svgCache')) {
    function svgCache(Svg $svg): Svg
    {
        dd(app(Factory::class)->svgCache($svg));
        // return app(Factory::class)->cache($svg);
    }
}
