<?php

declare(strict_types=1);

namespace BladeUI\Icons\Shapes;

<<<<<<< Updated upstream
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
=======
use BladeUI\Icons\SvgElement;
use NumPHP\Core\NumArray;

/**
 * Line
 */
class Line extends Shape
{
    /** @var float $x1 */
    protected float $x1;

    /** @var float $y1 */
    protected float $y1;

    /** @var float $x2 */
    protected float $x2;

    /** @var float $y2 */
    protected float $y2;

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

>>>>>>> Stashed changes
        $this->startPosition = new NumArray([
            'x' => $this->x1,
            'y' => $this->y1
        ]);
<<<<<<< Updated upstream
        $this->removeAtt('cx');
        $this->removeAtt('cy');
        $this->removeAtt('rx');
        $this->removeAtt('ry');
    }

    public function getLang()
=======
    }

    /**
     * getLang
     *
     * @return float
     */
    public function getLang(): float
>>>>>>> Stashed changes
    {
        return sqrt((pow(($this->x2 - $this->x1), 2) + pow(($this->x2 - $this->x1), 2)));
    }
}
