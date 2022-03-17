<?php

declare(strict_types=1);

namespace BladeUI\Icons\Configurators;

/**
 * Defs
 */
class Defs extends Configurator
{
    public function __construct(string $contents, array $attributes = [], $context = null)
    {
        foreach ($attributes as $key => $attribute) {
            $this->$key($attribute);
        }
        parent::__construct($contents,  $attributes, $context);
    }
}
