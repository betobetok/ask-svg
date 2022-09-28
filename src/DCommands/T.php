<?php

declare(strict_types=1);

namespace ASK\Svg\DCommands;

use ASK\Svg\Exceptions\ComandException;

/**
 * A comand "t" in a d attribute of a svg path
 * 
 *  T x y
 *  t dx dy
 */
class T extends Command
{
    public function initialization($cmdString)
    {
        preg_match_all('/(-?\.?[\d]+(?:\.[0-9]+)?(?:e-[0-9]+|e[0-9]+)?)(?:\s|,\s?)?(-?\.?[\d]+(?:\.[0-9]+)?(?:e-[0-9]+|e[0-9]+)?)(?:\s|,\s?)?/i', $cmdString, $parameters);

        /** a command t must have even nummer of parameters */
        if (count($parameters[0]) === 0) {
            throw ComandException::configuration(self::class, count($parameters), 2);
        }

        $count = 0;
        foreach ($parameters[0] as $k => $coordinate) {
            $coordinates[$k]['x'] = (float)$parameters[1][$k];
            $coordinates[$k]['y'] = (float)$parameters[2][$k];
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
