<?php

declare(strict_types=1);

namespace ASK\Svg\Configurators;

/**
 * ClipPhat in a svg document
 */
class ClipPhat extends Configurator
{
    public function __construct(string $contents, array $attributes = [], $context = null)
    {
        foreach ($attributes as $key => $attribute) {
            $this->$key($attribute);
        }

        parent::__construct($contents,  $attributes, $context);
    }
}
