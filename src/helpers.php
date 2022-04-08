<?php

declare(strict_types=1);
define('NEW_LINE', "");
define('TAB', "");

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
        return app(Factory::class)->svgCache($svg);
    }
}

if (!function_exists('array_merge_recursive_distinct')) {
    function array_merge_recursive_distinct(array &$array1, array &$array2)
    {
        $merged = $array1;
        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = array_merge_recursive_distinct($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}
