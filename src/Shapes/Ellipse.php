<?php

declare(strict_types=1);

namespace BladeUI\Icons\Shapes;

<<<<<<< Updated upstream
use NumPHP\Core\NumArray;

class Ellipse extends Shape
{
    protected float $cx;

    protected float $cy;

    protected float $rx;

    protected float $ry;

    public function __construct(string $name, string $contents, array $attributes = [], $context = null)
    {
        parent::__construct($name,  $contents,  $attributes, $context);
        $att = $this->attributes();
        $this->cx = (float)$att['cx'];
        $this->cy = (float)$att['cy'];
        $this->rx = (float)$att['rx'];
        $this->ry = (float)$att['ry'];
        $this->removeAtt('cx');
        $this->removeAtt('cy');
        $this->removeAtt('rx');
        $this->removeAtt('ry');
    }

    public function getCenter()
=======
use BladeUI\Icons\SvgElement;
use NumPHP\Core\NumArray;

/**
 * Ellipse
 */
class Ellipse extends Shape
{
    /** @var float $cx */
    protected float $cx;

    /** @var float $cy */
    protected float $cy;

    /** @var float $rx */
    protected float $rx;

    /** @var float $ry */
    protected float $ry;

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
>>>>>>> Stashed changes
    {
        return new NumArray([
            'x' => $this->cx,
            'y' => $this->cy,
        ]);
    }

<<<<<<< Updated upstream
    public function getRadioX()
=======
    /**
     * getRadioX
     *
     * @return float
     */
    public function getRadioX(): float
>>>>>>> Stashed changes
    {
        return $this->rx;
    }

<<<<<<< Updated upstream
    public function getRadioY()
=======
    /**
     * getRadioY
     *
     * @return float
     */
    public function getRadioY(): float
>>>>>>> Stashed changes
    {
        return $this->ry;
    }

<<<<<<< Updated upstream
    public function getArea()
=======
    /**
     * getArea
     *
     * @return float
     */
    public function getArea(): float
>>>>>>> Stashed changes
    {
        return pi() * $this->ry * $this->rx;
    }
}
