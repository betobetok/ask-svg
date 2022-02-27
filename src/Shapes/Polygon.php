<?php

declare(strict_types=1);

namespace BladeUI\Icons\Shapes;

<<<<<<< Updated upstream
=======
use BladeUI\Icons\SvgElement;
>>>>>>> Stashed changes
use Exception;
use NumPHP\Core\NumArray;

/**
 * Polygon
 */
class Polygon extends Shape
{
<<<<<<< Updated upstream
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
=======
    /** @var array $points */
    protected array $points;

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
            throw new Exception("Polygon had no points attribute", 1);
        }
        $this->points = $this->getPoints($this->points);
>>>>>>> Stashed changes
        if (!empty($this->points)) {
            $this->removeAtt('points');
        }
        $this->startPosition = $this->points[0];
    }

<<<<<<< Updated upstream
=======
    /**
     * toHtml
     *
     * @return string
     */
>>>>>>> Stashed changes
    public function toHtml(): string
    {
        return sprintf('<%s points="%s" %s/>', $this->name(), $this->pointsString(), $this->renderAttributes());
    }

    /**
     * getPoints
     *
<<<<<<< Updated upstream
     * @param string points
=======
     * @param string $points
>>>>>>> Stashed changes
     *
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
