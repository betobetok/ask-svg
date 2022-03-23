<?php

declare(strict_types=1);

<<<<<<< Updated upstream
namespace BladeUI\Icons\Commands;
=======
namespace ASK\Svg\DCommands;
>>>>>>> Stashed changes

use ASK\Svg\Exceptions\ComandException;

class L extends Command
{
<<<<<<< Updated upstream
    protected array $coordinates;

    public function initialization()
    {
        if (count($this->attributes) % 2 > 0) {
            throw new Error('Incorrect configuration of attributes');
=======
    public function initialization($parameters)
    {
        /** a command l must have even nummer of parameters */
        if (count($parameters) % 2 > 0 || count($parameters) <= 0) {
            throw ComandException::configuration(self::class, count($parameters), 2);
>>>>>>> Stashed changes
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
        $absolutePoint = $this->getEndPoint();
        $this->resetNext();
        $relativePoint = $this->getEndPoint(false);
        $this->resetNext();
        $this->setEndPoint($relativePoint, $absolutePoint);
        unset($parameters);
    }
}
