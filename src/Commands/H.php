<?php

declare(strict_types=1);

namespace ASK\Svg\DCommands;

use ASK\Svg\Exceptions\ComandException;

class H extends Command
{
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
}
