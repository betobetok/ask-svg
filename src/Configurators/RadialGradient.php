<?php

declare(strict_types=1);

namespace BladeUI\Icons\Configurators;

/**
 * RadialGradient
 */
class RadialGradient extends Configurator
{

    /** @var bool isTransformable */
    protected bool $isTransformable = false;

    /**
     *
     * @param string name
     * @param string contents
     * @param array attributes
     * @param SvgElement context
     *
     * @return void
     */
    public function __construct(string $name, string $contents, array $attributes = [], $context = null)
    {
        parent::__construct($name,  $contents,  $attributes, $context);
    }
}
