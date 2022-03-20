<?php

declare(strict_types=1);

namespace ASK\Svg\Commands;

use Error;

/**
 * A comand "q" in a d attribute of a svg path
 * 
 * Q x1 y1, x y
 * q dx1 dy1, dx dy
 */
class Q extends Command
{
    public function initialization()
    {
        /** a command q must have parameters in multiples of 4 */
        if (count($this->attributes) % 4 > 0) {
            throw new Error('Incorrect configuration of attributes');
        }

        $count = 0;
        foreach ($this->attributes as $k => $coordinate) {
            switch ($k % 4) {
                case 0:
                    $coordinates[$count]['x1'] = $coordinate;
                    break;
                case 1:
                    $coordinates[$count]['y1'] = $coordinate;
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
