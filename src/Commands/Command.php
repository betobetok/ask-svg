<?php

declare(strict_types=1);

namespace BladeUI\Icons\Commands;

use Illuminate\Contracts\Support\Htmlable;
use NumPHP\Core\NumArray;

abstract class Command implements Htmlable 
{
    protected string $type;

    protected array $coordinates;

    protected array $attributes;

    protected Command $prev;

    protected int $position;

    protected array $endPointCoordinates;


    public function __construct(string $type, array $attributes = [], ?Command $prev = null)
    {
        $this->type = $type;
        if (!empty($prev)) {
            $this->prev = $prev;
        }
        $this->attributes = $attributes;
        $this->initialization();
    }

    public function initialization()
    {
        # code...
    }

    public function getComand()
    {
        $name = explode('\\', get_class($this));
        return $this->type === 'relative' ? strtolower($name[count($name)-1]) : strtoupper($name[count($name)-1]);
    }

    public function getEndPoint($absolute = true)
    {
        return [
            'x' => 0,
            'y' => 0,
        ];
    }

    public function setEndPoint(array $relativePoint, array $absolutePoint)
    {
        $this->endPointCoordinates = [
            'x' => (float)$relativePoint['x'],
            'y' => (float)$relativePoint['y'],
            'X' => (float)$absolutePoint['x'],
            'Y' => (float)$absolutePoint['y'],
        ];

        $this->endPointAbs = new NumArray([
            (float)$absolutePoint['x'],
            (float)$absolutePoint['y'],
        ]);

        $this->endPointRel = new NumArray([
            (float)$relativePoint['x'],
            (float)$relativePoint['y'],
        ]);
    }

    public function toHtml()
    {
        $return = $this->getComand();
        foreach ($this->coordinates as $point) {
            if (is_array($point)) {
                foreach ($point as $coordinate) {
                    $return .= ' ' . $coordinate;
                }
                if ($point !== $this->coordinates[array_key_last($this->coordinates)]){
                    $return .= ',';
                }else{
                    $return .= ' ';
                }
            }
        }
        return $return;
    }
        
}
