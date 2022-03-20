<?php

declare(strict_types=1);

namespace ASK\Svg\Configurators;

/**
 * A Mask element in a svg document
 */
class Mask extends Configurator
{
    public function __construct(string $contents, array $attributes = [], $context = null)
    {
        foreach ($attributes as $key => $attribute) {
            $this->$key($attribute);
        }

        parent::__construct($contents,  $attributes, $context);
    }
}
