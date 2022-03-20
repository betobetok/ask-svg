<?php

declare(strict_types=1);

namespace ASK\Svg\Commands;

/**
 * A comand "z" in a d attribute of a svg path
 */
class Z extends Command
{
    public function initialization()
    {
        $this->coordinates = [];
    }

    public function getPoint($n = null, $absolute = true): array
    {
        $m = $this->getLastMComand();
        return $m->getEndPoint();
    }
}
