<?php

declare(strict_types=1);

namespace BladeUI\Icons\Commands;

use Error;

class A extends Command
{

    public function initialization()
    {
        if (count($this->attributes) % 7 > 0) {
            throw new Error('Incorrect configuration of attributes');
        }

        $count = 0;
        foreach ($this->attributes as $k => $condition) {
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
        unset($this->attributes);
    }

    public function getCenter($n = null)
    {
        if ($n >= $this->count) {
            throw new Error("Point doesn't exist, max position: " . $this->count, 1);
        }

        if ($n === null) {
            $n = $this->nextPoint;
            if ($this->nextPoint >= $this->count) {
                $this->nextPoint = 0;
            } else {
                $this->nextPoint++;
            }
        }

        if ($n < 1) {
            $aPoint = $this->prev->getEndPoint();
        } else {
            $aPoint = $this->getPoint($n - 1);
        }

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
