<?php

declare(strict_types=1);

namespace ASK\Svg\DCommands;

use ASK\Svg\Exceptions\ComandException;

/**
 * A comand "z" in a d attribute of a svg path
 */
class Z extends Command
{
    public function initialization($parameters)
    {
        if (!empty($parameters)) {
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
