<?php

declare(strict_types=1);

namespace BladeUI\Icons\Commands;

use Error;

class H extends Command
{
    protected float $x;

    public function initialization()
    {
        if (count($this->attributes) <= 0) {
            throw new Error('Incorrect configuration of attributes');
        }

        foreach ($this->attributes as $k => $coordinate) {
            $coordinates[$k]['x'] = $coordinate;
            $this->x = (float)$coordinate;
        }
        $this->count = count($this->attributes);
        $this->coordinates = $coordinates;
        $absolutePoint = $this->getEndPoint();
        $this->resetNext();
        $relativePoint = $this->getEndPoint(false);
        $this->resetNext();
        $this->setEndPoint($relativePoint, $absolutePoint);

        unset($this->attributes);
    }
}
