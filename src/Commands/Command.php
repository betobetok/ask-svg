<?php

declare(strict_types=1);

<<<<<<< Updated upstream
namespace BladeUI\Icons\Commands;

use BladeUI\Icons\Shapes\Path;
=======
namespace ASK\Svg\DCommands;

use ASK\Svg\Exceptions\ComandException;
>>>>>>> Stashed changes
use Illuminate\Contracts\Support\Htmlable;
use NumPHP\Core\NumArray;

<<<<<<< Updated upstream
=======
/**
 * 
 * A command in a d attribute of a svg path
 * @ignore
 */
>>>>>>> Stashed changes
abstract class Command implements Htmlable
{

    /** nextPoint @var int */
    protected $nextPoint = 0;

    /** @var mixed $count */
    protected $count = 0;

    /** @var string $type */
    protected string $type;

    /** @var array $coordinates */
    protected array $coordinates;

    /** @var Command $prev */
    protected Command $prev;

    /** @var int $position */
    protected int $position;

    /** @var array $endPointCoordinates */
    protected array $endPointCoordinates;


    public function __construct(string $type, array $parameters = [], ?Command $prev = null)
    {
        $this->type = $type;
        if (!empty($prev)) {
            $this->prev = $prev;
        }

        $this->initialization($parameters);
    }

    /**
     * initialization
     *
     * @param  mixed $parameters
     * @return void
     */
    abstract public function initialization($parameters);

    /**
     * getComand
     *
     * @return string
     */
    public function getComand(): string
    {
        $name = explode('\\', get_class($this));
        return $this->type === 'relative' ? strtolower($name[count($name) - 1]) : strtoupper($name[count($name) - 1]);
    }

    /**
     * setEndPoint
     *
     * @param array relativePoint
     * @param array absolutePoint
     *
     * @return void
     */
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
        if (strtolower($return) === 'z') {
            return $return . ' ';
        }
        foreach ($this->coordinates as $point) {
            if (is_array($point)) {
                foreach ($point as $coordinate) {
                    $return .= ' ' . $coordinate;
                }
                if ($point !== $this->coordinates[array_key_last($this->coordinates)]) {
                    $return .= ',';
                } else {
                    $return .= ' ';
                }
            }
        }
        return $return;
    }

    /**
     * getEndPoint
     *
     * @param bool absolute
     *
     * @return array
     */
    public function getEndPoint(bool $absolute = true): array
    {
        $a = $this->count - 1;
        if($a !== count($this->coordinates)-1){
            dump([$this]);
        }
        return $this->getPoint($a, $absolute);
    }

    /**
     * resetNext
     *
     * @return void
     */
    public function resetNext()
    {
        $this->nextPoint = 0;
    }

    /**
     * getLastMComand
     *
     * @return Command|null
     */
    public function getLastMComand(): ?Command
    {
        $command = $this->getComand();
        if (strtolower($command) === 'm') {
            return $this;
        } else {
            return $this->prev === null ? null : $this->prev->getLastMComand();
        }
    }

    public function getPoint($n = null, $absolute = true): array
    {
        if ($n >= $this->count) {
            throw ComandException::pointNotFound($n, $this->count);
        }
        if ($n === null) {
            $n = $this->nextPoint;
            if ($this->nextPoint >= $this->count) {
                $this->nextPoint = 0;
            } else {
                $this->nextPoint++;
            }
        }
        if ($absolute && $this->type === 'absolute') {
            return [
                'x' => $this->coordinates[$n]['x'] ?? 0,
                'y' => $this->coordinates[$n]['y'] ?? 0,
            ];
        }
        if ($absolute && $this->type === 'relative') {
            if (empty($this->prev)) {
                return [
                    'x' => $this->coordinates[$n]['x'] ?? 0,
                    'y' => $this->coordinates[$n]['y'] ?? 0,
                ];
            }
            $prevPoint = $this->prev->getEndPoint();
            return [
                'x' => ($prevPoint['x'] ?? 0) + ($this->coordinates[$n]['x'] ?? 0),
                'y' => ($prevPoint['y'] ?? 0) + ($this->coordinates[$n]['y'] ?? 0),
            ];
        }
        if (!$absolute && $this->type === 'absolute') {
            if (empty($this->prev)) {
                return [
                    'x' => $this->coordinates[$n]['x'] ?? 0,
                    'y' => $this->coordinates[$n]['y'] ?? 0,
                ];
            }
            $prevPoint = $this->prev->getEndPoint();
            return [
                'x' => ($this->coordinates[$n]['x'] ?? 0) - ($prevPoint['x'] ?? 0),
                'y' => ($this->coordinates[$n]['y'] ?? 0) - ($prevPoint['y'] ?? 0),
            ];
        }
        if (!$absolute && $this->type === 'relative') {
            return [
                'x' => $this->coordinates[$n]['x'] ?? 0,
                'y' => $this->coordinates[$n]['y'] ?? 0,
            ];
        }
    }
}
