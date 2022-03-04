<?php

declare(strict_types=1);

namespace BladeUI\Icons\Commands;

use Error;

class V extends Command
{
    protected float $y;

    public function initialization()
    {
        if (count($this->attributes) <= 0) {
            throw new Error('Incorrect configuration of attributes');
        }

        foreach ($this->attributes as $k => $coordinate) {
            $coordinates[$k]['x'] = 0;
            $coordinates[$k]['y'] = $coordinate;
            $this->x = (float)$coordinate;
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
    
}
