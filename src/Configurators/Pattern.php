<?php

declare(strict_types=1);

namespace ASK\Svg\Configurators;

/**
 * A Pattern element in a svg document
 */
class Pattern extends Configurator
{
    public function __construct(string $contents, array $attributes = [], $context = null)
    {
        $this->isTransformable = true;

        foreach ($attributes as $key => $attribute) {
            $this->$key($attribute);
        }

        parent::__construct($contents,  $attributes, $context);
    }
}
