<?php

declare(strict_types=1);

namespace ASK\Svg\Shapes;

use ASK\Svg\SvgElement;
use NumPHP\Core\NumArray;

/**
 * A Ellipse element in a svg document
 */
class Ellipse extends Shape
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
        $this->startPosition = $this->center();
    }

    /**
     * get the Center of the ellipse
     *
     * @return NumArray
     */
    public function center(): NumArray
    {
        return new NumArray([
            $this->cx,
            $this->cy,
        ]);
    }

    /**
     * get the x Radio of the ellipse
     *
     * @return float
     */
    public function radioX(): float
    {
        return $this->rx;
    }

    /**
     * get the y Radio of the ellipse
     *
     * @return float
     */
    public function radioY(): float
    {
        return $this->ry;
    }

    /**
     * get the Area of the ellipse
     *
     * @return float
     */
    public function area(): float
    {
        return pi() * $this->ry * $this->rx;
    }
}
