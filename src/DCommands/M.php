<?php

declare(strict_types=1);

namespace ASK\Svg\DCommands;

use ASK\Svg\Exceptions\ComandException;

/**
 * A comand "m" in a d attribute of a svg path
 * 
 * M x y
 * m dx dy
 */
class M extends Command
{
    /** @var float x */
    private float $x;

    /** @var float y */
    private float $y;

    public function initialization($cmdString)
    {
        preg_match_all('/(-?\.?[\d]+(?:\.[0-9]+)?(?:e-[0-9]+|e[0-9]+)?)(?:\s|,\s?)?(-?\.?[\d]+(?:\.[0-9]+)?(?:e-[0-9]+|e[0-9]+)?)(?:\s|,\s?)?/i', $cmdString, $parameters);

        /** a command m must have even nummer of parameters */
        if (count($parameters[0]) === 0) {
            throw ComandException::configuration(self::class, count($parameters), 2);
        }
        $count = 0;
        foreach ($parameters[0] as $k => $coordinate) {
            $coordinates[$k]['x'] = (float)$parameters[1][$k];
            $coordinates[$k]['y'] = (float)$parameters[2][$k];
            $count++;
        }
        $this->coordinates = $coordinates;
        $this->count = $count;
        $this->x = (float)$parameters[0];
        $this->y = (float)$parameters[1];
        $absolutePoint = $this->getEndPoint();
        $relativePoint = $this->getEndPoint(false);
        $this->setEndPoint($relativePoint, $absolutePoint);
        unset($parameters);
    }

    /**
     * getMDinstance get the distance between last m point and the point of parameter
     *
     * @param array toPoint
     *
     * @return array
     */
    public function getMDinstance(array $toPoint = []): array
    {
        $fromPoint = [
            'x' => $this->x,
            'y' => $this->y
        ];

        return parent::getDinstance($fromPoint, $toPoint);
    }

    /**
     * Get the value of x
     *
     * @return float
     */
    public function getX(): float
    {
        return $this->x;
    }

    /**
     * Set the value of x
     *
     * @param  float $x
     * @return self
     */
    public function setX(float $x): self
    {
        $this->x = $x;

        return $this;
    }

    /**
     * Get the value of y
     *
     * @return float
     */
    public function getY(): float
    {
        return $this->y;
    }

    /**
     * Set the value of y
     *
     * @param  float $y
     * @return self
     */
    public function setY(float $y): self
    {
        $this->y = $y;

        return $this;
    }
}
