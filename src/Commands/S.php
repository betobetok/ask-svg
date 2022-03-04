<?php

declare(strict_types=1);

namespace BladeUI\Icons\Commands;

use Error;

class S extends Command
{
    public function initialization()
    {
        if (count($this->attributes) % 4 > 0) {
            throw new Error('Incorrect configuration of attributes');
        }

        $count = 0;
        foreach ($this->attributes as $k => $coordinate) {
            switch ($k % 4) {
                case 0:
                    $coordinates[$count]['x2'] = $coordinate;
                    break;
                case 1:
                    $coordinates[$count]['y2'] = $coordinate;
                    break;
                case 2:
                    $coordinates[$count]['x'] = $coordinate;
                    break;
                case 3:
                    $coordinates[$count]['y'] = $coordinate;
                    $count++;
                    break;
            }
        }
        $this->coordinates = $coordinates;
        $this->count = $count;
        $absolutePoint = $this->getEndPoint();
        $this->resetNext();
        $relativePoint = $this->getEndPoint(false);
        $this->resetNext();
        $this->setEndPoint($relativePoint, $absolutePoint);
        unset($this->attributes);
    }
}
