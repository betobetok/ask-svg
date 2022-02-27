<?php

declare(strict_types=1);

namespace BladeUI\Icons\Conteiners;

use NumPHP\Core\NumArray;

interface Conteiner
{
    public function getContent();
    public function setContent($content = null);
}
