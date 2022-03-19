<?php

declare(strict_types=1);

namespace BladeUI\Icons\Shapes;

use BladeUI\Icons\SvgElement;
use NumPHP\Core\NumArray;

/**
 * Line
 */
class Line extends Shape
{
    /** @var float $x1 */
    protected float $x1 = 0;

    /** @var float $y1 */
    protected float $y1 = 0;

    /** @var float $x2 */
    protected float $x2 = 0;

    /** @var float $y2 */
    protected float $y2 = 0;

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

        $this->startPosition = new NumArray([
            'x' => $this->x1,
            'y' => $this->y1
        ]);
    }

    /**
     * getLang
     *
     * @return float
     */
    public function getLang(): float
    {
        return sqrt((pow(($this->x2 - $this->x1), 2) + pow(($this->x2 - $this->x1), 2)));
    }
}
