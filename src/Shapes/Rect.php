<?php

declare(strict_types=1);

namespace BladeUI\Icons\Shapes;

use BladeUI\Icons\SvgElement;
use NumPHP\Core\NumArray;

/**
 * Rect
 */
class Rect extends Shape
{
    /** @var float $x */
    protected float $x = 0;

    /** @var float $y */
    protected float $y = 0;

    /** @var float $width */
    protected float $width = 0;

    /** @var float $height */
    protected float $height = 0;

    /** @var float $rx */
    protected float $rx = 0;

    /** @var float $ry */
    protected float $ry = 0;

    /**
     * __construct
     *
     * @param  string $contents
     * @param  array $attributes
     * @param  SvgElement $context
     * @return void
     */
    public function __construct(string $contents, array $attributes = [], SvgElement $context = null)
    {
        parent::__construct($contents, $attributes, $context);
        $att = $this->attributes();
        foreach ($att as $k => $val) {
            if (property_exists($this, $k)) {
                $this->$k = (float)$val;
                $this->removeAtt($k);
            }
        }
    }

    /**
     * getCenter
     *
     * @return NumArray
     */
    public function getCenter(): NumArray
    {
        return new NumArray([
            'x' => $this->x + $this->width / 2,
            'y' => $this->y + $this->height / 2,
        ]);
    }

    /**
     * getArea
     *
     * @return float
     */
    public function getArea(): float
    {
        return $this->x * $this->y;
    }
}
