<?php

declare(strict_types=1);

namespace ASK\Svg\DCommands;

use ASK\Svg\Exceptions\ComandException;
use Illuminate\Contracts\Support\Htmlable;
use NumPHP\Core\NumArray;

/**
 * 
 * A command in a *d* attribute of a svg path
 * 
 * There are five line commands for &lt;path&gt; nodes. 
 * * M - *Move*
 * * L - *Line*
 * * H - *Horizontal*
 * * V - *Vertical*
 * * Z - *Close*
 * 
 * und five arc  commands.
 * * C - *Cubic Curve*
 * * Q - *Quadratic Curve*
 * * S - *Short Cubic Curve*
 * * T - *Together Multiple Quadratic Curve*
 * * A - *Arc*
 * 
 * Each command contains a $coordinates array with all the parameters of each point, 
 * as well as a reference to the previous command.
 * 
 * 
 */
abstract class Command implements Htmlable
{

    /** @var int $nextPoint*/
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


    public function __construct(string $type, string $parameters = '', ?Command $prev = null)
    {
        $this->type = $type;
        if (!empty($prev)) {
            $this->prev = $prev;
        }

        $this->initialization($parameters);
    }

    /**
     * initialization is a configuration method for the specific type of command
     *
     * @param  mixed $parameters
     * @return void
     */
    abstract public function initialization($parameters);

    /**
     * getComand 
     * 
     * return the name of the command. Uppercase if it's absolute lowercase if relative 
     *
     * @return string
     */
    public function getComand(): string
    {
        $name = explode('\\', get_class($this));
        return $this->type === 'relative' ? strtolower($name[count($name) - 1]) : strtoupper($name[count($name) - 1]);
    }

    /**
     * setEndPoint set the values of the coordinates of the end point in the command list, both Absolute and Relative
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
     * getEndPoint returns an array with the x and y value of the end point. If the parameter "absolute" 
     * is put to true the Absolute value of the end point is returned, relative is returned otherwise
     *
     * @param bool absolute
     *
     * @return array
     */
    public function getEndPoint(bool $absolute = true): array
    {
        $a = $this->count - 1;
        if ($a !== count($this->coordinates) - 1) {
            dump([$this, $a]);
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
     * getLastMComand returns the last M command in the "d" attribute
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

    /**
     * getPoint returns the array with the x and y parameters of the n point, 
     * if the parameter "absolute" is set to true, the Absolute values are returned, 
     * relative are retuned otherwise
     *
     * @param  int $n
     * @param  bool $absolute
     * @return array
     */
    public function getPoint(int $n = null, bool $absolute = false): array
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

    /**
     * getDinstance get the distance between tow points, 
     * if the second parameter is not gived, returns the Absolut distans of the point
     *
     * @param array fromPoint
     * @param array toPoint
     *
     * @return array
     */
    public function getDinstance(array $fromPoint, array $toPoint = []): array
    {
        if (empty($toPoint)) {
            $toPoit = [
                'x' => 0,
                'y' => 0,
            ];
        }
        $dx = $fromPoint['x'] - $toPoint['x'];
        $dy = $fromPoint['y'] - $toPoint['y'];
        $distance = sqrt(pow($dx, 2) + (pow($dy, 2)));
        return [
            'dx' => $dx,
            'dy' => $dy,
            'distance' => $distance
        ];
    }
}
