<?php

declare(strict_types=1);

namespace ASK\Svg\Shapes;

use ASK\Svg\SvgElement;
use NumPHP\Core\NumArray;

/**
 * A Line element in a svg document
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
     * get teh Logitud of the line
     *
     * @return float
     */
    public function long(): float
    {
        return sqrt((pow(($this->x2 - $this->x1), 2) + pow(($this->x2 - $this->x1), 2)));
    }
}
