<?php

declare(strict_types=1);

namespace ASK\Svg\DCommands;

use ASK\Svg\Exceptions\ComandException;

/**
 * A comand "h" in a d attribute of a svg path
 * 
 * H x
 * h dx
 */
class H extends Command
{
    /** @var float $x */
    protected float $x;

    public function initialization($parameters)
    {
        if (count($parameters) <= 0) {
            throw ComandException::configuration(self::class, count($parameters), 1);
        }

        foreach ($parameters as $k => $coordinate) {
            $coordinates[$k]['x'] = $coordinate;
            $this->x = (float)$coordinate;
        }
        $this->count = count($parameters);
        $this->coordinates = $coordinates;
        $absolutePoint = $this->getEndPoint();
        $this->resetNext();
        $relativePoint = $this->getEndPoint(false);
        $this->resetNext();
        $this->setEndPoint($relativePoint, $absolutePoint);

        unset($parameters);
    }

    /**
     * getX
     *
     * @return float
     */
    public function getX(): float
    {
        return $this->x;
    }

    /**
     * setX Set the value of x
     *
     * @param  float $x
     * @return self
     */
    public function setX(float $x): self
    {
        $this->x = $x;

        return $this;
    }
}
