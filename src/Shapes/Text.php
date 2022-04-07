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
    /** @var string textContent */
    private $textContent;

    public function __construct(array $attributes = [], SvgElement $context = null, string $contet = '')
    {
        $this->textContent = $contet;
        parent::__construct($attributes, $context);
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
        return $this->textContent;
    }

    public function setContent($content)
    {
    }
}
