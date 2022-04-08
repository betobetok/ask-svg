<?php

declare(strict_types=1);

use ASK\Svg\DCommands\C;

$c = new C('absolute', [
    0 => 10,
    1 => 10,
    2 => 15,
    3 => 16,
    4 => 0,
    5 => 0
]);

echo $c->getComand();
