<?php

declare(strict_types=1);

namespace BladeUI\Icons\Configurators;

<<<<<<< Updated upstream
use BladeUI\Icons\Concerns\RendersAttributes;
use NumPHP\Core\NumArray;

=======
/**
 * RadialGradient
 */
>>>>>>> Stashed changes
class RadialGradient extends Configurator
{

    /** @var bool isTransformable */
    protected bool $isTransformable = false;

<<<<<<< Updated upstream
=======
    /**
     *
     * @param string name
     * @param string contents
     * @param array attributes
     * @param SvgElement context
     *
     * @return void
     */
>>>>>>> Stashed changes
    public function __construct(string $name, string $contents, array $attributes = [], $context = null)
    {
        parent::__construct($name,  $contents,  $attributes, $context);
    }
}
