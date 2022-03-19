<?php

declare(strict_types=1);

namespace BladeUI\Icons\Shapes;

use BladeUI\Icons\SvgElement;
use NumPHP\Core\NumArray;

/**
 * Ellipse
 */
class Ellipse extends Shape
{
    /** @var float $cx */
    protected float $cx = 0;

    /** @var float $cy */
    protected float $cy = 0;

    /** @var float $rx */
    protected float $rx = 0;

    /** @var float $ry */
    protected float $ry = 0;

    /**
     * 
     *
     * @param string $contents
     * @param array $attributes
     * @param SvgElement $context
     *
     * @return void
     */
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
     * getCenter
     *
     * @return NumArray
     */
    public function getCenter(): NumArray
    {
        return new NumArray([
            'x' => $this->cx,
            'y' => $this->cy,
        ]);
    }

    /**
     * getRadioX
     *
     * @return float
     */
    public function getRadioX(): float
    {
        return $this->rx;
    }

    /**
     * getRadioY
     *
     * @return float
     */
    public function getRadioY(): float
    {
        return $this->ry;
    }

    /**
     * getArea
     *
     * @return float
     */
    public function getArea(): float
    {
        return pi() * $this->ry * $this->rx;
    }
}
