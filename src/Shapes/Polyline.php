<?php

declare(strict_types=1);

namespace BladeUI\Icons\Shapes;

use Exception;
use NumPHP\Core\NumArray;

/**
 * Polyline
 */
class Polyline extends Shape
{
    /** @var array points */
    protected array $points;

    public function __construct(string $name, string $contents, array $attributes = [], $context = null)
    {
        parent::__construct($name,  $contents,  $attributes, $context);
        preg_match('/\spoints="([A-Za-z0-9\s.,-]+)"/', $contents, $points);

        if (empty($points)) {
            throw new Exception("Path had no d", 1);
        }
        $this->points = $this->getPoints($points[1]);
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
