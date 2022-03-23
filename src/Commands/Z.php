<?php

declare(strict_types=1);

<<<<<<< Updated upstream
namespace BladeUI\Icons\Commands;
=======
namespace ASK\Svg\DCommands;

use ASK\Svg\Exceptions\ComandException;
>>>>>>> Stashed changes

class Z extends Command
{
    public function initialization($parameters)
    {
        if (count($parameters) !== 0) {
            throw ComandException::configuration(self::class, count($parameters), 0);
        }
        $this->coordinates = [];
    }

    public function getPoint($n = null, $absolute = true): array
    {
        $m = $this->getLastMComand();
        return $m->getEndPoint();
    }
}
