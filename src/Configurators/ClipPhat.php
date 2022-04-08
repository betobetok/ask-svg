<?php

declare(strict_types=1);

namespace ASK\Svg\Configurators;

use ASK\Svg\Conteiner;

/**
 * ClipPhat in a svg document
 */
class ClipPhat extends Configurator implements Conteiner
{
    public function __construct(string $contents, array $attributes = [], $context = null)
    {
        foreach ($attributes as $key => $attribute) {
            $this->$key($attribute);
        }

        parent::__construct($contents,  $attributes, $context);
    }
    public function getContent()
    {
    }
    public function setContent($content)
    {
    }
}
