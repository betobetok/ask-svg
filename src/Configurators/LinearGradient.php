<?php

declare(strict_types=1);

namespace BladeUI\Icons\Configurators;

use BladeUI\Icons\Concerns\RendersAttributes;
use NumPHP\Core\NumArray;

class LinearGradient extends Configurator
{

    /** @var bool isTransformable */
    protected bool $isTransformable = true;

    public function __construct(string $name, string $contents, array $attributes = [], $context = null)
    {
        parent::__construct($name,  $contents,  $attributes, $context);
    }
}
