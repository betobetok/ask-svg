<?php

declare(strict_types=1);

namespace ASK\Svg\Commands;

use Error;

/** 
 * A comand "c" in a d attribute of a svg path
 * 
 * C x1 y1, x2 y2, x y
 * c dx1 dy1, dx2 dy2, dx dy
 */
class C extends Command
{
    public function initialization()
    {
        /** a command c must have parameters in multiples of 6 */
        if (count($this->attributes) % 6 > 0) {
            throw new Error('Incorrect configuration of attributes ');
        }

        $count = 0;
        foreach ($this->attributes as $k => $coordinate) {
            switch ($k % 6) {
                case 0:
                    $coordinates[$count]['x1'] = $coordinate;
                    break;
                case 1:
                    $coordinates[$count]['y1'] = $coordinate;
                    break;
                case 2:
                    $coordinates[$count]['x2'] = $coordinate;
                    break;
                case 3:
                    $coordinates[$count]['y2'] = $coordinate;
                    break;
                case 4:
                    $coordinates[$count]['x'] = $coordinate;
                    break;
                case 5:
                    $coordinates[$count]['y'] = $coordinate;
                    $count++;
                    break;
            }
        }
        $this->coordinates = $coordinates;
        $this->count = $count;
        $absolutePoint = $this->getEndPoint();
        $relativePoint = $this->getEndPoint(false);
        $this->setEndPoint($relativePoint, $absolutePoint);
        unset($this->attributes);
    }
}
