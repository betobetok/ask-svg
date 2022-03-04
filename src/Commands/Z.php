<?php

declare(strict_types=1);

namespace BladeUI\Icons\Commands;

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
