<?php

declare(strict_types=1);

namespace ASK\Svg\Configurators;

use ASK\Svg\Conteiner;
use ASK\Svg\SvgElement;

/**
 * A unknown Svg Element
 * @deprecated v1.0.0
 */
class Stop extends Configurator
{
    public function __construct(string $contents, array $attributes = [], SvgElement $context = null)
    {
        parent::__construct($contents,  $attributes,  $context);
    }
}
