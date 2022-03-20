<?php

declare(strict_types=1);

namespace ASK\Svg\Configurators;


/**
 * A Font element into a svg document
 */
class Font extends Configurator
{
    public function __construct(string $contents, array $attributes = [], $context = null)
    {
        foreach ($attributes as $key => $attribute) {
            $this->$key($attribute);
        }

        parent::__construct($contents,  $attributes, $context);
    }
}
