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
    public function __construct(string $contents, array $attributes = [], $context = null)
    {
        $this->isTransformable = true;

        foreach ($attributes as $key => $attribute) {
            $this->$key($attribute);
        }

        parent::__construct($contents,  $attributes, $context);
    }
}
