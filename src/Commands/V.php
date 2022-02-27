<?php

declare(strict_types=1);

namespace BladeUI\Icons\Commands;

use Error;

class V extends Command
{
    protected float $y;

    public function initialization()
    {
        if (count($this->attributes) <= 0) {
            throw new Error('Incorrect configuration of attributes');
        }

        foreach ($this->attributes as $k => $coordinate) {
            $this->coordinates['y'] = $coordinate;
            $this->y = (float)$coordinate;
        }

        $absolutePoint = $this->getEndPoint();
        $relativePoint = $this->getEndPoint(false);
        $this->setEndPoint($relativePoint, $absolutePoint);
        unset($this->attributes);
    }

    public function getEndPoint($absolute = true)
    {
        if ($absolute && $this->type === 'absolute') {
            if (empty($this->prev)) {
                return [
                    'x' => 0,
                    'y' => $this->coordinates['y'],
                ];
            }
            $prevPoint = $this->prev->getEndPoint();
            return [
                'x' => $prevPoint['x'],
                'y' => $this->coordinates['y'],
            ];
        }
        if ($absolute && $this->type === 'relative') {
            if (empty($this->prev)) {
                return [
                    'x' => 0,
                    'y' => $this->coordinates['y'],
                ];
            }
            $prevPoint = $this->prev->getEndPoint();
            return [
                'x' => $prevPoint['x'],
                'y' => $prevPoint['y'] + $this->coordinates['y'],
            ];
        }
        if (!$absolute && $this->type === 'absolute') {
            if (empty($this->prev)) {
                return [
                    'x' => 0,
                    'y' => $this->coordinates['y'],
                ];
            }
            $prevPoint = $this->prev->getEndPoint();
            return [
                'x' => 0,
                'y' => $this->coordinates['y'] - $prevPoint['y'],
            ];
        }
        if (!$absolute && $this->type === 'relative') {
            return [
                'x' => 0,
                'y' => $this->coordinates['y'],
            ];
        }
    }
}
