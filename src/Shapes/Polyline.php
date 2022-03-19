<?php

declare(strict_types=1);

namespace BladeUI\Icons\Shapes;

use BladeUI\Icons\SvgElement;
use Exception;
use NumPHP\Core\NumArray;

/**
 * Polyline
 */
class Polyline extends Shape
{
    /** @var array $points */
    protected array $points = [];

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
        if (isset($this->attributes()['points']) && !empty($this->attributes()['points'])) {
            $this->points = $this->attributes()['points'];
        } else {
            $this->points = '';
        }
        $this->points = $this->getPoints($this->points);
        if (!empty($this->points)) {
            $this->removeAtt('points');
        }
        $this->startPosition = $this->points[0];
    }

    public function toHtml(): string
    {
        return sprintf('<%s points="%s" %s/>', $this->name(), $this->pointsString(), $this->renderAttributes());
    }

    /**
     * getPoints
     *
     * @param  string $points
     * @return array
     */
    public function getPoints(string $points): array
    {
        $points2ret = [];
        preg_match_all('/([e0-9\s.-]+),([e0-9\s.-]+)/', $points, $match);
        foreach ($match[1] as $k => $name) {
            $points2ret[$k] = new NumArray([
                'x' => $match[1],
                'y' => $match[2]
            ]);
        }
        return $points2ret;
    }
}
