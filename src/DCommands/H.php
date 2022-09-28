<?php

declare(strict_types=1);

namespace ASK\Svg\DCommands;

use ASK\Svg\Exceptions\ComandException;

/**
 * A comand "h" in a d attribute of a svg path
 * 
 * A command draws a horizontal line, this command only take 
 * one parameter since they only move in one direction.
 * 
 * H x
 * h dx
 */
class H extends Command
{
    /** @var float $x */
    protected float $x;

    public function initialization($cmdString)
    {
        preg_match_all('/(-?\.?[\d]+(?:\.[0-9]+)?(?:e-[0-9]+|e[0-9]+)?)(?:\s|,\s?)?/i', $cmdString, $parameters);

        if (count($parameters[0]) <= 0) {
            throw ComandException::configuration(self::class, count($parameters), 1);
        }

        foreach ($parameters[0] as $k => $coordinate) {
            $coordinates[$k]['x'] = (float)$parameters[1][$k];
            $this->x = (float)$parameters[1][$k];
        }
        $this->count = count($parameters[0]);
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
