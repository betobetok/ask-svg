<?php

declare(strict_types=1);

namespace BladeUI\Icons\Configurators;

use BladeUI\Icons\Concerns\RendersAttributes;
use BladeUI\Icons\SvgElement;

/**
 * Configurator
 */
class Configurator extends SvgElement
{
<<<<<<< Updated upstream
    /** @var bool isTransformable */
    protected bool $isTransformable = false;

    public function __construct(string $name, string $contents, array $attributes = [], $context = null)
    {
=======
    public function __construct(string $contents, array $attributes = [], SvgElement $context = null)
    {
        $name = explode('\\', __CLASS__);
        $name = strtolower($name[array_key_last($name)]);

        $this->contents = $contents;

        $contents = $this->configAttributesAndContent($name, $contents, $attributes);

>>>>>>> Stashed changes
        parent::__construct($name, $contents, $attributes, $context);
    }
}
