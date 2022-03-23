<?php

declare(strict_types=1);

namespace ASK\Svg\DCommands;

use ASK\Svg\Exceptions\ComandException;

class L extends Command
{
    public function initialization($parameters)
    {
        /** a command l must have even nummer of parameters */
        if (count($parameters) % 2 > 0 || count($parameters) <= 0) {
            throw ComandException::configuration(self::class, count($parameters), 2);
        }

        $count = 0;
        foreach ($parameters as $k => $coordinate) {
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
        $absolutePoint = $this->getEndPoint();
        $this->resetNext();
        $relativePoint = $this->getEndPoint(false);
        $this->resetNext();
        $this->setEndPoint($relativePoint, $absolutePoint);
        unset($parameters);
    }
}
