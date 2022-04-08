<?php

declare(strict_types=1);

namespace ASK\Svg\Configurators;

use ASK\Svg\Conteiner;
use ASK\Svg\SvgElement;

/**
 * A unknown Svg Element
 * @deprecated v1.0.0
 */
class uSvgElement extends SvgElement implements Conteiner
{
    public function __construct(string $name, string $contents, array $attributes = [], SvgElement $context = null)
    {
        parent::__construct($name,  $contents,  $attributes,  $context);
    }
    public function getContent()
    {
    }
    public function setContent($content)
    {
    }
}
