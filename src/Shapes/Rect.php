<?php

declare(strict_types=1);

namespace ASK\Svg\Shapes;

use ASK\Svg\SvgElement;
use NumPHP\Core\NumArray;

/**
 * A Rect element in a svg document
 */
class Rect extends Shape
{
    public function __construct(array $attributes = [], SvgElement $context = null)
    {
        parent::__construct($attributes, $context);
        foreach ($attributes as $k => $val) {
            if (property_exists($this, $k)) {
                $this->$k = (float)$val;
                $this->removeAtt($k);
            }
        }
    }

    /**
     * get the Center of the rectangle
     *
     * @return NumArray
     */
    public function center(): NumArray
    {
        return new NumArray([
            'x' => $this->x + $this->width / 2,
            'y' => $this->y + $this->height / 2,
        ]);
    }

    /**
     * get the Area of the rectangle
     *
     * @return float
     */
    public function area(): float
    {
        return $this->x * $this->y;
    }
}
