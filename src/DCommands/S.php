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
    public function initialization($cmdString)
    {
        preg_match_all('/(-?\.?[\d]+(?:\.[0-9]+)?(?:e-[0-9]+|e[0-9]+)?)(?:\s|,\s?)?(-?\.?[\d]+(?:\.[0-9]+)?(?:e-[0-9]+|e[0-9]+)?)(?:\s|,\s?)(-?\.?[\d]+(?:\.[0-9]+)?(?:e-[0-9]+|e[0-9]+)?)(?:\s|,\s?)?(-?\.?[\d]+(?:\.[0-9]+)?(?:e-[0-9]+|e[0-9]+)?)(?:\s|,\s?)/i', $cmdString, $parameters);

        /** a command s must have parameters in multiples of 4 */
        if (count($parameters[0]) === 0) {
            throw ComandException::configuration(self::class, count($parameters), 4);
        }

        $count = 0;
        foreach ($parameters[0] as $k => $coordinate) {
            $coordinates[$k]['x2'] = (float)$parameters[1][$k];
            $coordinates[$k]['y2'] = (float)$parameters[2][$k];
            $coordinates[$k]['x'] = (float)$parameters[3][$k];
            $coordinates[$k]['y'] = (float)$parameters[4][$k];
            $count++;
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
