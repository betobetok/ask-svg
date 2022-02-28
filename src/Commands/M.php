<?php

declare(strict_types=1);

namespace BladeUI\Icons\Commands;

use Error;

class M extends Command
{
    private $x;
    private $y;

    public function initialization()
    {
        if (count($this->attributes) % 2 > 0) {
            throw new Error('Incorrect configuration of attributes');
        }
        $count = 0;
        foreach ($this->attributes as $k => $coordinate) {
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
        $this->x = $this->attributes[0];
        $this->y = $this->attributes[1];
        $absolutePoint = $this->getEndPoint();
        $relativePoint = $this->getEndPoint(false);
        $this->setEndPoint($relativePoint, $absolutePoint);
        unset($this->attributes);
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
