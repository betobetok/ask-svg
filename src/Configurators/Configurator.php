<?php

declare(strict_types=1);

namespace BladeUI\Icons\Configurators;

use BladeUI\Icons\Concerns\RendersAttributes;
use BladeUI\Icons\SvgElement;

/**
 * Configurator
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
