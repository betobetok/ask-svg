<?php

declare(strict_types=1);

namespace BladeUI\Icons\Shapes;

<<<<<<< Updated upstream
use BladeUI\Icons\Commands as Comands;
=======
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

    protected array $startPosition;

    protected array $endPosition;

    protected array $commands;

=======
    
    /** @var NumArray $startPosition */
    protected NumArray $startPosition;
    
    /** @var bool $isTransformable */
    protected bool $isTransformable = true;
    
    /**
     * getStartPosition
     *
     * @return void
     */
>>>>>>> Stashed changes
    public function getStartPosition()
    {
        return $this->startPosition;
    }
}
