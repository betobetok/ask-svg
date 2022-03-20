<?php

declare(strict_types=1);

namespace ASK\Svg\Commands;

use Error;

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

    public function initialization()
    {
        /** a command m must have even nummer of parameters */
        if (count($this->attributes) % 2 > 0) {
            throw new Error('Incorrect configuration of attributes');
        }
        $count = 0;
        foreach ($this->attributes as $k => $coordinate) {
            switch ($k % 2) {
                case 0:
                    $coordinates[$count]['x'] = $coordinate;
                    break;
                case 1:
                    $coordinates[$count]['y'] = $coordinate;
                    $count++;
                    break;
            }
        }
        $this->coordinates = $coordinates;
        $this->count = $count;
        $this->x = $this->attributes[0];
        $this->y = $this->attributes[1];
        $absolutePoint = $this->getEndPoint();
        $relativePoint = $this->getEndPoint(false);
        $this->setEndPoint($relativePoint, $absolutePoint);
        unset($this->attributes);
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
