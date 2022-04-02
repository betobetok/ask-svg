<?php

declare(strict_types=1);

namespace ASK\Svg\DCommands;

use ASK\Svg\Exceptions\ComandException;

/**
 * A comand "v" in a d attribute of a svg path
 * 
 * V y
 * v dy
 */
class V extends Command
{
    /** @var float $y */
    protected float $y;

    public function initialization($cmdString)
    {
        preg_match_all('/(-?\.?[\d]+(?:\.[0-9]+)?(?:e-[0-9]+|e[0-9]+)?)(?:\s|,)?/', $cmdString, $parameters);

        if (count($parameters[0]) === 0) {
            throw ComandException::configuration(self::class, count($parameters), 1);
        }

        foreach ($parameters[0] as $k => $coordinate) {
            $coordinates[$k]['y'] = (float)$parameters[1][$k];
            $this->y = (float)$parameters[1][$k];
        }
        $this->coordinates = $coordinates;
        $this->count = count($parameters[0]);
        $absolutePoint = $this->getEndPoint();
        $this->resetNext();
        $relativePoint = $this->getEndPoint(false);
        $this->resetNext();
        $this->setEndPoint($relativePoint, $absolutePoint);
        unset($parameters);
    }

    /**
     * getY
     *
     * @return float
     */
    public function getY(): float
    {
        return $this->y;
    }

    /**
     * setY
     *
     * @param float y
     *
     * @return self
     */
    public function setY(float $y)
    {
        $this->y = $y;

        return $this;
    }
}
