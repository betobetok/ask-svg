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
    /** @var bool isTransformable */
    protected bool $isTransformable = false;

    public function __construct(string $name, string $contents, array $attributes = [], $context = null)
    {
        parent::__construct($name, $contents, $attributes, $context);
    }
}
