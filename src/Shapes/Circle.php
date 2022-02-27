<?php

declare(strict_types=1);

namespace BladeUI\Icons\Shapes;

<<<<<<< Updated upstream
use NumPHP\Core\NumArray;

class Circle extends Shape
{
=======
use BladeUI\Icons\SvgElement;
use NumPHP\Core\NumArray;

/**
 * Circle
 */
class Circle extends Shape
{
    /** @var float $cx */
>>>>>>> Stashed changes
    protected float $cx;

    /** @var float $cy */
    protected float $cy;

    /** @var float $r */
    protected float $r;

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
<<<<<<< Updated upstream
        parent::__construct($name,  $contents,  $attributes, $context);
        $att = $this->attributes();
        $this->cx = (float)$att['cx'];
        $this->cy = (float)$att['cy'];
        $this->r = (float)$att['r'];
        $this->removeAtt('cx');
        $this->removeAtt('cy');
        $this->removeAtt('r');
=======
        parent::__construct($contents,  $attributes, $context);

        $att = $this->attributes();
        foreach ($att as $k => $val) {
            if (property_exists($this, $k)) {
                $this->$k = (float)$val;
                $this->removeAtt($k);
            }
        }
>>>>>>> Stashed changes
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

<<<<<<< Updated upstream
    public function getDiameter()
=======
    /**
     * getDiameter
     *
     * @return float
     */
    public function getDiameter(): float
>>>>>>> Stashed changes
    {
        return $this->r * 2;
    }

    /**
     * getRadio
     *
     * @return float
     */
    public function getRadio(): float
    {
        return $this->r;
    }

    /**
     * getArea
     *
     * @return float
     */
    public function getArea(): float
    {
        return pi() * pow($this->r, 2);
    }
}
