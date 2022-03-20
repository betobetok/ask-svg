<?php

declare(strict_types=1);

namespace ASK\Svg\Commands;

use Error;

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

    public function initialization()
    {
        if (count($this->attributes) <= 0) {
            throw new Error('Incorrect configuration of attributes');
        }

        foreach ($this->attributes as $k => $coordinate) {
            $coordinates[$k]['y'] = $coordinate;
            $this->y = (float)$coordinate;
        }
        $this->coordinates = $coordinates;
        $this->count = count($this->attributes);
        $absolutePoint = $this->getEndPoint();
        $this->resetNext();
        $relativePoint = $this->getEndPoint(false);
        $this->resetNext();
        $this->setEndPoint($relativePoint, $absolutePoint);
        unset($this->attributes);
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
