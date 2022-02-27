<?php

declare(strict_types=1);

namespace BladeUI\Icons\Shapes;

use NumPHP\Core\NumArray;

/**
 * Text
 */
class Text extends Shape
{

    public function __construct(string $name, string $contents, array $attributes = [], $context = null)
    {
        parent::__construct($name,  $contents,  $attributes, $context);
        $att = $this->attributes();
    }
}
