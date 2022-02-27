<?php

declare(strict_types=1);

namespace BladeUI\Icons\Commands;

use Error;

class Q extends Command
{
    protected $nextPoint = 0;

    protected $count = 0;

    public function initialization()
    {
        if (count($this->attributes) % 4 > 0) {
            throw new Error('Incorrect configuration of attributes');
        }

        $count = 0;
        foreach ($this->attributes as $k => $coordinate) {
            switch ($k % 4) {
                case 0:
                    $coordinates[$count]['x1'] = $coordinate;
                    break;
                case 1:
                    $coordinates[$count]['y1'] = $coordinate;
                    break;
                case 2:
                    $coordinates[$count]['x'] = $coordinate;
                    break;
                case 3:
                    $coordinates[$count]['y'] = $coordinate;
                    $count++;
                    break;
            }
        }
        $this->coordinates = $coordinates;
        $this->count = $count;
        $absolutePoint = $this->getEndPoint();
        $relativePoint = $this->getEndPoint(false);
        $this->setEndPoint($relativePoint, $absolutePoint);
        unset($this->attributes);
    }

    public function getEndPoint($absolute = true)
    {
        $n = $this->count - 1;
        return $this->getPoint($n, $absolute);
    }

    public function getPoint($n = null, $absolute = true)
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
        if ($absolute && $this->type === 'absolute') {
            return [
                'x' => $this->coordinates[$n]['x'],
                'y' => $this->coordinates[$n]['y'],
            ];
        }
        if ($absolute && $this->type === 'relative') {
            if (empty($this->prev)) {
                return [
                    'x' => $this->coordinates[$n]['x'],
                    'y' => $this->coordinates[$n]['y'],
                ];
            }
            $prevPoint = $this->prev->getEndPoint();
            return [
                'x' => $prevPoint['x'] + $this->coordinates[$n]['x'],
                'y' => $prevPoint['y'] + $this->coordinates[$n]['y'],
            ];
        }
        if (!$absolute && $this->type === 'absolute') {
            if (empty($this->prev)) {
                return [
                    'x' => $this->coordinates[$n]['x'],
                    'y' => $this->coordinates[$n]['y'],
                ];
            }
            $prevPoint = $this->prev->getEndPoint();
            return [
                'x' => $this->coordinates[$n]['x'] - $prevPoint['x'],
                'y' => $this->coordinates[$n]['y'] - $prevPoint['y'],
            ];
        }
        if (!$absolute && $this->type === 'relative') {
            return [
                'x' => $this->coordinates[$n]['x'],
                'y' => $this->coordinates[$n]['y'],
            ];
        }
    }

    public function resetNext()
    {
        $this->nextPoint = 1;
    }
}
