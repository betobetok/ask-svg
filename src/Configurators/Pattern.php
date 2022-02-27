<?php

declare(strict_types=1);

namespace BladeUI\Icons\Configurators;

use BladeUI\Icons\Concerns\RendersAttributes;
use NumPHP\Core\NumArray;

/**
 * Pattern
 */
class Pattern extends Configurator
{

    /** @var bool $isTransformable */
    protected bool $isTransformable = true;

    /**
     * 
     *
     * @param string $name
     * @param string $contents
     * @param array $attributes
     * @param mixed $context
     *
     * @return void
     */
    public function __construct(string $name, string $contents, array $attributes = [], $context = null)
    {
        parent::__construct($name,  $contents,  $attributes, $context);
    }
}
