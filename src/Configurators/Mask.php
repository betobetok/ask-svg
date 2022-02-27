<?php

declare(strict_types=1);

namespace BladeUI\Icons\Configurators;

use BladeUI\Icons\Concerns\RendersAttributes;
use NumPHP\Core\NumArray;

class Mask extends Configurator
{

<<<<<<< Updated upstream
    /** @var bool isTransformable */
=======
    /** @var bool $isTransformable */
>>>>>>> Stashed changes
    protected bool $isTransformable = true;

    public function __construct(string $name, string $contents, array $attributes = [], $context = null)
    {
        parent::__construct($name,  $contents,  $attributes, $context);
    }
}
