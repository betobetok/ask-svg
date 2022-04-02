<?php

declare(strict_types=1);

namespace ASK\Svg\DCommands;

use ASK\Svg\Exceptions\ComandException;

/**
 * A comand "q" in a d attribute of a svg path
 * 
 * Q x1 y1, x y
 * q dx1 dy1, dx dy
 */
class Q extends Command
{
    public function initialization($cmdString)
    {
        preg_match_all('/(-?\.?[\d]+(?:\.[0-9]+)?(?:e-[0-9]+|e[0-9]+)?)(?:\s|,)?(-?\.?[\d]+(?:\.[0-9]+)?(?:e-[0-9]+|e[0-9]+)?)(?:\s|,)?(-?\.?[\d]+(?:\.[0-9]+)?(?:e-[0-9]+|e[0-9]+)?)(?:\s|,)?(-?\.?[\d]+(?:\.[0-9]+)?(?:e-[0-9]+|e[0-9]+)?)(?:\s|,)?/', $cmdString, $parameters);

        /** a command q must have parameters in multiples of 4 */
        if (count($parameters[0]) === 0) {
            throw ComandException::configuration(self::class, count($parameters), 4);
        }

        $count = 0;
        foreach ($parameters[0] as $k => $coordinate) {
            $coordinates[$k]['x1'] = (float)$parameters[1][$k];
            $coordinates[$k]['y1'] = (float)$parameters[2][$k];
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
