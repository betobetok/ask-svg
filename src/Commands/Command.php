<?php

declare(strict_types=1);

namespace BladeUI\Icons\Commands;

use NumPHP\Core\NumArray;

abstract class Command
{
    protected string $type;

    protected string $comand;

    protected array $attributes;

    protected Command $prev;

    protected int $position;

    protected array $endPointCoordinates;

    private const COMMANDS = [
        'moves' => [
            'relative' => 'm',
            'absolute' => 'M'
        ],
        'lines' => [
            'relative' => 'l',
            'absolute' => 'L'
        ],
        'verticals' => [
            'relative' => 'v',
            'absolute' => 'V'
        ],
        'horisontals' => [
            'relative'=>'h',
            'absolute'=>'H'
        ],
        'curves' => [
            'relative'=>'c',
            'absolute'=>'C'
        ],
        'severalCurves' => [
            'relative'=>'s',
            'absolute'=>'S'
        ],
        'quadraticCurves' => [
            'relative'=>'q',
            'absolute'=>'Q'
        ],
        'TCurves' => [
            'relative'=>'t',
            'absolute'=>'T'
        ],
        'arcs' => [
            'relative'=>'a',
            'absolute'=>'A'
        ],
    ];


    public function __construct(string $type, array $attributes = [], ?Command $prev = null)
    {
        $this->type = $type;
        if(!empty($prev)){
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
        return $this->type === 'relative' ? strtolower(self::class) : self::class;
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
}