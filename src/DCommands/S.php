<?php

declare(strict_types=1);

namespace ASK\Svg\DCommands;

use ASK\Svg\Exceptions\ComandException;

/**
 * A comand "s" in a d attribute of a svg path
 * 
 * S x2 y2, x y
 * s dx2 dy2, dx dy
 */
class S extends Command
{
    public function initialization($parameters)
    {
        /** a command s must have parameters in multiples of 4 */
        if (count($parameters) % 4 > 0 || count($parameters) === 0) {
            throw ComandException::configuration(self::class, count($parameters), 4);
        }

        $count = 0;
        foreach ($parameters as $k => $coordinate) {
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
        unset($parameters);
    }
}
