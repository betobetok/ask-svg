<?php

declare(strict_types=1);

namespace ASK\Svg\Shapes;

use ASK\Svg\Conteiner;
use ASK\Svg\SvgElement;

/**
 * a Text element in a svg document
 */
class Text extends Shape implements Conteiner
{
    public function __construct(string $contents, array $attributes = [], SvgElement $context = null)
    {
        parent::__construct($contents,  $attributes, $context);
        $att = $this->attributes();
        foreach ($att as $k => $val) {
            if (property_exists($this, $k)) {
                $this->$k = $val;
                $this->removeAtt($k);
            }
        }
    }
    public function getContent()
    {
    }
    public function setContent($content)
    {
    }
}
