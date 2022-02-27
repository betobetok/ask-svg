<?php

declare(strict_types=1);

namespace BladeUI\Icons\Shapes;

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
    {
        return new NumArray([
            'x' => $this->cx,
            'y' => $this->cy,
        ]);
    }

    public function getRadioX()
    {
        return $this->rx;
    }

    public function getRadioY()
    {
        return $this->ry;
    }

    public function getArea()
    {
        return pi() * $this->ry * $this->rx;
    }
}
