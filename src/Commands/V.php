<?php

declare(strict_types=1);

<<<<<<< Updated upstream
namespace BladeUI\Icons\Commands;
=======
namespace ASK\Svg\DCommands;
>>>>>>> Stashed changes

use ASK\Svg\Exceptions\ComandException;

class V extends Command
{
    protected float $y;

    public function initialization($parameters)
    {
        if (count($parameters) <= 0 || count($parameters) === 0) {
            throw ComandException::configuration(self::class, count($parameters), 1);
        }

        foreach ($parameters as $k => $coordinate) {
            $coordinates[$k]['y'] = $coordinate;
            $this->y = (float)$coordinate;
        }
        $this->coordinates = $coordinates;
        $this->count = count($parameters);
        $absolutePoint = $this->getEndPoint();
        $this->resetNext();
        $relativePoint = $this->getEndPoint(false);
        $this->resetNext();
        $this->setEndPoint($relativePoint, $absolutePoint);
        unset($parameters);
    }
    
}
