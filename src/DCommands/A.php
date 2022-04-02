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

    public function initialization($cmdString)
    {
        preg_match_all('/(-?\.?[\d]+(?:\.[0-9]+)?(?:e-[0-9]+|e[0-9]+)?)(?:\s|,)?(-?\.?[\d]+(?:\.[0-9]+)?(?:e-[0-9]+|e[0-9]+)?)(?:\s|,)?(-?\.?[\d]+(?:\.[0-9]+)?(?:e-[0-9]+|e[0-9]+)?)(?:\s|,)([01]{1})(?:\s|,)?([01]{1})(?:\s|,)?(-?\.?[\d]+(?:\.[0-9]+)?(?:e-[0-9]+|e[0-9]+)?)(?:\s|,)?(-?\.?[\d]+(?:\.[0-9]+)?(?:e-[0-9]+|e[0-9]+)?)/', $cmdString, $parameters);

        /** a command a must have parameters in multiples of 7 */
        if (count($parameters[0]) <= 0) {
            throw ComandException::configuration(self::class, count($parameters), 7);
        }

        $count = 0;
        foreach ($parameters[0] as $k => $condition) {
            $coordinates[$k]['rx'] = (float)$parameters[1][$k];
            $coordinates[$k]['ry'] = (float)$parameters[2][$k];
            $coordinates[$k]['xRotation'] = (float)$parameters[3][$k];
            $coordinates[$k]['large'] = (float)$parameters[4][$k];
            $coordinates[$k]['sweep'] = (float)$parameters[5][$k];
            $coordinates[$k]['x'] = (float)$parameters[6][$k];
            $coordinates[$k]['y'] = (float)$parameters[7][$k];
            $count++;
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
