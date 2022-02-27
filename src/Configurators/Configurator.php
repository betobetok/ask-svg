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
        $name = explode('\\', __CLASS__);
        $name = strtolower($name[array_key_last($name)]);

        $this->contents = $contents;

        $contents = $this->configAttributesAndContent($name, $contents, $attributes);

        parent::__construct($name, $contents, $attributes, $context);
    }
}
