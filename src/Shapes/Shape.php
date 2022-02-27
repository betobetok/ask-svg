<?php

declare(strict_types=1);

namespace BladeUI\Icons\Shapes;

<<<<<<< Updated upstream
<<<<<<< Updated upstream
use BladeUI\Icons\Commands as Comands;
=======
=======
>>>>>>> Stashed changes
use BladeUI\Icons\SvgElement;
use Illuminate\Contracts\Support\Htmlable;
use NumPHP\Core\NumArray;
>>>>>>> Stashed changes

/**
 * Shape
 */
abstract class Shape extends SvgElement
{
<<<<<<< Updated upstream

<<<<<<< Updated upstream
    protected array $startPosition;

    protected array $endPosition;
=======
    /** @var NumArray $startPosition */
    protected NumArray $startPosition;

    public function __construct(string $contents, array $attributes = [], SvgElement $context = null)
    {
        $name = explode('\\', __CLASS__);
        $name = strtolower($name[array_key_last($name)]);
>>>>>>> Stashed changes

        $this->isTransformable = true;

<<<<<<< Updated upstream
=======
    
    /** @var NumArray $startPosition */
    protected NumArray $startPosition;
    
    /** @var bool $isTransformable */
    protected bool $isTransformable = true;
    
=======
        $contents = $this->configAttributesAndContent($name, $contents, $attributes);

        parent::__construct($name,  $contents,  $attributes, $context);
    }
>>>>>>> Stashed changes
    /**
     * getStartPosition
     *
     * @return void
     */
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
    public function getStartPosition()
    {
        return $this->startPosition;
    }
}
