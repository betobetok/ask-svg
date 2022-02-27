<?php

declare(strict_types=1);

namespace BladeUI\Icons\Shapes;

use NumPHP\Core\NumArray;

class Line extends Shape
{
    protected float $x1;

    protected float $y1;

    protected float $x2;

    protected float $y2;

    public function __construct(string $name, string $contents, array $attributes = [], $context = null)
    {
        parent::__construct($name,  $contents,  $attributes, $context);
        $att = $this->attributes();
        $this->x1 = (float)$att['cx'];
        $this->y1 = (float)$att['cy'];
        $this->x2 = (float)$att['rx'];
        $this->y2 = (float)$att['ry'];
        $this->startPosition = new NumArray([
            'x' => $this->x1,
            'y' => $this->y1
        ]);
        $this->removeAtt('cx');
        $this->removeAtt('cy');
        $this->removeAtt('rx');
        $this->removeAtt('ry');
    }

    public function getLang()
    {
        return sqrt((pow(($this->x2 - $this->x1), 2) + pow(($this->x2 - $this->x1), 2)));
    }
}
