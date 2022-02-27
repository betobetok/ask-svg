<?php

declare(strict_types=1);

namespace BladeUI\Icons\Shapes;

use NumPHP\Core\NumArray;

class Circle extends Shape
{
    protected float $cx;

    protected float $cy;

    protected float $r;

    public function __construct(string $name, string $contents, array $attributes = [], $context = null)
    {
        parent::__construct($name,  $contents,  $attributes, $context);
        $att = $this->attributes();
        $this->cx = (float)$att['cx'];
        $this->cy = (float)$att['cy'];
        $this->r = (float)$att['r'];
        $this->removeAtt('cx');
        $this->removeAtt('cy');
        $this->removeAtt('r');
    }

    public function getCenter()
    {
        return new NumArray([
            'x' => $this->cx,
            'y' => $this->cy,
        ]);
    }

    public function getDiameter()
    {
        return $this->r * 2;
    }

    public function getRadio()
    {
        return $this->r;
    }

    public function getArea()
    {
        return pi() * pow($this->r, 2);
    }
}
