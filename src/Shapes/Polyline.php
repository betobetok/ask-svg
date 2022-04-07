<?php

declare(strict_types=1);

namespace ASK\Svg\Shapes;

use ASK\Svg\Configurators\uSvgElement;
use ASK\Svg\SvgElement;
use NumPHP\Core\NumArray;
use Illuminate\Support\Str;

/**
 * A Polyline element in a svg document
 */
class Polyline extends Shape
{
    /** @var array $points */
    protected array $points = [];

    public function __construct(array $attributes = [], SvgElement $context = null)
    {
        parent::__construct($attributes, $context);
        if (isset($attributes['points']) && !empty($attributes['points'])) {
            $points = $attributes['points'];
        } else {
            $points = '';
        }

        $this->points = $this->getPoints($points);
        if (!empty($this->points)) {
            $this->removeAtt('points');
        }
        $this->startPosition = $this->points[0];
    }

    /**
     * (overloaded Method from SvgElement)
     *
     * @return string
     */
    public function toHtml(): string
    {
        return sprintf('<%s %s />', $this->name(), $this->renderAttributes());
    }

    /**
     * get the array Points from a string 
     *
     * @param string $points
     *
     * @return array
     */
    public function getPoints(string $points): array
    {
        $points2ret = [];
        preg_match_all('/(-?\.?[\d]+(?:\.[0-9]+)?(?:e-[0-9]+|e[0-9]+)?)(?:\s|,)?(-?\.?[\d]+(?:\.[0-9]+)?(?:e-[0-9]+|e[0-9]+)?)(?:\s|,)?/', $points, $match);
        foreach ($match[1] as $k => $name) {
            $points2ret[$k] = new NumArray([
                'x' => $match[1][$k],
                'y' => $match[2][$k]
            ]);
        }
        return $points2ret;
    }

    public function pointsString(): string
    {
        $pointsStr = '';
        foreach ($this->points as $k => $point) {
            $pointArray = $point->getData();
            $pointsStr .= sprintf('%s,%s', $pointArray['x'], $pointArray['y']);
            if (array_key_last($this->points) !== $k) {
                $pointsStr .= ' ';
            }
        }
        return $pointsStr;
    }

    /**
     * attributes
     *
     * @return array
     */
    public function attributes(): array
    {
        $attributes = parent::attributes();
        $attributes['points'] = $this->pointsString();
        return $attributes;
    }
}
