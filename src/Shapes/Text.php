<?php

declare(strict_types=1);

namespace BladeUI\Icons\Shapes;

<<<<<<< Updated upstream
=======
use BladeUI\Icons\SvgElement;
>>>>>>> Stashed changes
use NumPHP\Core\NumArray;

/**
 * Text
 */
class Text extends Shape
{

<<<<<<< Updated upstream
    public function __construct(string $name, string $contents, array $attributes = [], $context = null)
    {
        parent::__construct($name,  $contents,  $attributes, $context);
        $att = $this->attributes();
=======
    /**
     * 
     * @param string $name
     * @param string $contents
     * @param array $attributes
     * @param SvgElement $context
     *
     * @return void
     */
    public function __construct(string $contents, array $attributes = [], SvgElement $context = null)
    {
        parent::__construct($contents,  $attributes, $context);
        $att = $this->attributes();
        foreach ($att as $k => $val) {
            if (property_exists($this, $k)) {
                $this->$k = $val;
                $this->removeAtt($k);
            }
        }
>>>>>>> Stashed changes
    }
}
