<?php

declare(strict_types=1);

namespace ASK\Svg\DCommands;

use ASK\Svg\Exceptions\ComandException;

/**
 * A comand "a" in a d attribute of a svg path
 * 
 * Arcs are sections of circles or ellipses. 
 * For a given x-radius and y-radius, there are two ellipses that can 
 * connect any two points (last end point and (x, y)). 
 * Along either of those circles, there are two possible paths that can 
 * be taken to connect the points (large way or short way) so in any situation, 
 * there are four possible arcs available.
 * 
 * Because of that, arcs require seven parameters:
 * A rx ry x-axis-rotation large-arc-flag sweep-flag x y
 * 
 * a rx ry x-axis-rotation large-arc-flag sweep-flag dx dy
 * 
 * A command hat in aditional to the other commands a getCenter Methode
 */
class A extends Command
{

    public function initialization($parameters)
    {
        /** a command a must have parameters in multiples of 7 */
        if (count($this->parameters) % 7 > 0 || count($parameters) <= 0) {
            throw ComandException::configuration(self::class, count($parameters), 7);
        }

        $count = 0;
        foreach ($parameters as $k => $condition) {
            switch ($k % 7) {
                case 0:
                    $coordinates[$count]['rx'] = $condition;
                    break;
                case 1:
                    $coordinates[$count]['ry'] = $condition;
                    break;
                case 2:
                    $coordinates[$count]['xRotation'] = $condition;
                    break;
                case 3:
                    $coordinates[$count]['large'] = $condition;
                    break;
                case 4:
                    $coordinates[$count]['sweep'] = $condition;
                    break;
                case 5:
                    $coordinates[$count]['x'] = $condition;
                    break;
                case 6:
                    $coordinates[$count]['y'] = $condition;
                    $count++;
                    break;
            }
        }
        $this->coordinates = $coordinates;
        $this->count = $count;
        $absolutePoint = $this->getEndPoint();
        $this->resetNext();
        $relativePoint = $this->getEndPoint(false);
        $this->resetNext();
        $this->setEndPoint($relativePoint, $absolutePoint);
        unset($parameters);
    }

    /**
     * getCenter get the centero of the n arc 
     *
     * @param  int $n the arc number of which we want the center 
     * @return array
     */
    public function getCenter(int $n = null): array
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

        /** first point aPoint, if ist the first parameters group in the a command, the aPoint must be taken from the previus command */
        if ($n < 1) {
            $aPoint = $this->prev->getEndPoint();
        } else {
            $aPoint = $this->getPoint($n - 1);
        }

        /** last point bPoint*/
        $bPoint = $this->getPoint($n);

        $angle = $this->coordinates[$n]['xRotation'] * 180 / pi();

        if ($angle > 0) {
            $aPoint = [
                'x' => $aPoint['x'] * cos($angle) + $aPoint['y'] * sin($angle),
                'y' => $aPoint['y'] * cos($angle) - $aPoint['x'] * sin($angle),
            ];

            $bPoint = [
                'x' => $bPoint['x'] * cos($angle) + $bPoint['y'] * sin($angle),
                'y' => $bPoint['y'] * cos($angle) - $bPoint['x'] * sin($angle),
            ];
        }

        $arcRatio = (pow($this->coordinates[$n]['rx'], 2) / pow($this->coordinates[$n]['ry'], 2));
        $dx = ($aPoint['x'] - $bPoint['x']);
        $dy = ($aPoint['y'] - $bPoint['y']);

        $h = (($arcRatio * $dy) + pow($aPoint['x'], 2) - pow($bPoint['x'], 2)) / (2 * $dx);
        $k = $bPoint['y'] + pow($this->coordinates[$n]['rx'], 2) + ($arcRatio * pow(($bPoint['x'] - $h), 2));

        return [
            'x' => $h,
            'y' => $k
        ];
    }
}
