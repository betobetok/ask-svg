<?php

declare(strict_types=1);

namespace ASK\Svg\Configurators;

use ASK\Svg\SvgElement;

/**
 * Configurator is an element within the document that is used to set or modify the behavior of the svg
 */
class Configurator extends SvgElement
{
    public function __construct(string $contents, array $attributes = [], SvgElement $context = null)
    {
        $name = explode('\\', get_class($this));
        $name = strtolower($name[array_key_last($name)]);
        $this->contents = $contents;
        parent::__construct($name, $contents, $attributes, $context);
    }
}
