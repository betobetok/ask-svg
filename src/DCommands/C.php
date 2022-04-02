<?php

declare(strict_types=1);

namespace ASK\Svg\DCommands;

use ASK\Svg\Exceptions\ComandException;

/** 
 * A comand "c" in a d attribute of a svg path
 * 
 * The cubic curve, C, is the slightly more complex curve. 
 * Cubic Béziers take in two control points for each point. 
 * Therefore, to create a cubic Bézier, three sets of coordinates need to be specified.
 * 
 * C x1 y1, x2 y2, x y
 * c dx1 dy1, dx2 dy2, dx dy
 */
class C extends Command
{
    public function initialization($cmdString)
    {
        preg_match_all('/(-?\.?[\d]+(?:\.[0-9]+)?(?:e-[0-9]+|e[0-9]+)?)(?:\s|,)?(-?\.?[\d]+(?:\.[0-9]+)?(?:e-[0-9]+|e[0-9]+)?)(?:\s|,)?(-?\.?[\d]+(?:\.[0-9]+)?(?:e-[0-9]+|e[0-9]+)?)(?:\s|,)?(-?\.?[\d]+(?:\.[0-9]+)?(?:e-[0-9]+|e[0-9]+)?)(?:\s|,)?(-?\.?[\d]+(?:\.[0-9]+)?(?:e-[0-9]+|e[0-9]+)?)(?:\s|,)?(-?\.?[\d]+(?:\.[0-9]+)?(?:e-[0-9]+|e[0-9]+)?)(?:\s|,)?/', $cmdString, $parameters);
        /** a command c must have parameters in multiples of 6 */
        if (count($parameters[0]) <= 0) {
            throw ComandException::configuration(self::class, count($parameters), 6);
        }

        $count = 0;
        foreach ($parameters[0] as $k => $coordinate) {
            $coordinates[$k]['x1'] = (float)$parameters[1][$k];
            $coordinates[$k]['y1'] = (float)$parameters[2][$k];
            $coordinates[$k]['x2'] = (float)$parameters[3][$k];
            $coordinates[$k]['y2'] = (float)$parameters[4][$k];
            $coordinates[$k]['x'] = (float)$parameters[5][$k];
            $coordinates[$k]['y'] = (float)$parameters[6][$k];
            $count++;
        }
        $this->coordinates = $coordinates;
        $this->count = $count;
        $absolutePoint = $this->getEndPoint();
        $relativePoint = $this->getEndPoint(false);
        $this->setEndPoint($relativePoint, $absolutePoint);
        unset($parameters);
    }
}
