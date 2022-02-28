<?php

declare(strict_types=1);

namespace BladeUI\Icons\Shapes;

use BladeUI\Icons\SvgElement;
use Illuminate\Contracts\Support\Htmlable;
use NumPHP\Core\NumArray;

/**
 * Shape
 */
abstract class Shape extends SvgElement
{

    /** @var NumArray $startPosition */
    protected NumArray $startPosition;

    public function __construct(string $contents, array $attributes = [], SvgElement $context = null)
    {
        $name = explode('\\', get_class($this));
        $name = strtolower($name[array_key_last($name)]);

        $this->isTransformable = true;

        $contents = $this->configAttributesAndContent($name, $contents, $attributes);

        parent::__construct($name,  $contents,  $attributes, $context);
    }
    /**
     * getStartPosition
     *
     * @return void
     */
    public function getStartPosition()
    {
        return $this->startPosition;
    }
}
