<?php

declare(strict_types=1);

namespace ASK\Svg\DCommands;

use ASK\Svg\Exceptions\ComandException;

class M extends Command
{
    private $x;
    private $y;

    public function initialization($parameters)
    {
        /** a command m must have even nummer of parameters */
        if (count($parameters) % 2 > 0 || count($parameters) === 0) {
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
        $this->x = (float)$parameters[0];
        $this->y = (float)$parameters[1];
        $absolutePoint = $this->getEndPoint();
        $relativePoint = $this->getEndPoint(false);
        $this->setEndPoint($relativePoint, $absolutePoint);
        unset($parameters);
    }

    public function getX()
    {
        return $this->x;
    }

    public function getY()
    {
        return $this->y;
    }

    public function getDinstance($toPoint = [])
    {
        if (empty($toPoint)) {
            $toPoit = [
                'x' => 0,
                'y' => 0,
            ];
        }
        $dx = $this->x - $toPoint['x'];
        $dy = $this->y - $toPoint['y'];
        $distance = sqrt(pow($dx, 2) + (pow($dy, 2)));
        return [
            'dx' => $dx,
            'dy' => $dy,
            'distance' => $distance
        ];
    }
}
