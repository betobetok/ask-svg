<?php

declare(strict_types=1);

namespace BladeUI\Icons\Configurators;

use BladeUI\Icons\Concerns\RendersAttributes;
use NumPHP\Core\NumArray;

<<<<<<< Updated upstream
class Pattern extends Configurator
{

    /** @var bool isTransformable */
    protected bool $isTransformable = true;

=======
/**
 * Pattern
 */
class Pattern extends Configurator
{

    /** @var bool $isTransformable */
    protected bool $isTransformable = true;

    /**
     * 
     *
     * @param string $name
     * @param string $contents
     * @param array $attributes
     * @param mixed $context
     *
     * @return void
     */
>>>>>>> Stashed changes
    public function __construct(string $name, string $contents, array $attributes = [], $context = null)
    {
        parent::__construct($name,  $contents,  $attributes, $context);
    }
}
