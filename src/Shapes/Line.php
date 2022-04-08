<?php

declare(strict_types=1);

namespace ASK\Svg\Shapes;

use ASK\Svg\Concerns\RendersAttributes;
use ASK\Svg\SvgElement;
use NumPHP\Core\NumArray;

/**
 * A Line element in a svg document
 */
class Line extends Shape
{
    public function __construct(array $attributes = [], SvgElement $context = null)
    {
        parent::__construct($attributes, $context);
        $att = $this->attributes();
        foreach ($att as $k => $val) {
            if (property_exists($this, $k)) {
                $this->$k = (float)$val;
                $this->removeAtt($k);
            }
        }

        $this->startPosition = new NumArray([
            'x' => $this->x1,
            'y' => $this->y1
        ]);
    }

    /**
     * get teh Logitud of the line
     *
     * @return float
     */
    public function long(): float
    {
        return sqrt((pow(($this->x2 - $this->x1), 2) + pow(($this->x2 - $this->x1), 2)));
    }
}
