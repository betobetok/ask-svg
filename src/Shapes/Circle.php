<?php

declare(strict_types=1);

namespace ASK\Svg\Shapes;

use ASK\Svg\SvgElement;
use NumPHP\Core\NumArray;

/**
 * A Circle element in a svg document
 */
class Circle extends Shape
{
    /** @var float $cx */
    protected float $cx = 0;

    /** @var float $cy */
    protected float $cy = 0;

    /** @var float $r */
    protected float $r = 0;

    public function __construct(string $contents, array $attributes = [], SvgElement $context = null)
    {
        parent::__construct($contents,  $attributes, $context);

        $att = $this->attributes();
        foreach ($att as $k => $val) {
            if (property_exists($this, $k)) {
                $this->$k = (float)$val;
                $this->removeAtt($k);
            }
        }
    }

    /**
     * get the center of the circle
     *
     * @return NumArray
     */
    public function center(): NumArray
    {
        return new NumArray([
            'x' => $this->cx,
            'y' => $this->cy,
        ]);
    }

    /**
     * get the Diameter of the circle
     *
     * @return float
     */
    public function diameter(): float
    {
        return $this->r * 2;
    }

    /**
     * get the Radio of the circle
     *
     * @return float
     */
    public function radio(): float
    {
        return $this->r;
    }

    /**
     * get the Area of the circle
     *
     * @return float
     */
    public function area(): float
    {
        return pi() * pow($this->r, 2);
    }
}
