<?php

declare(strict_types=1);

namespace BladeUI\Icons\Commands;

use Error;

class H extends Command
{
    protected float $x;

    public function initialization()
    {
        if (count($this->attributes) <= 0) {
            throw new Error('Incorrect configuration of attributes');
        }

        foreach ($this->attributes as $k => $coordinate) {
            $this->coordinate['x'] = $coordinate;
            $this->x = (float)$coordinate;
        }

        $absolutePoint = $this->getEndPoint();
        $relativePoint = $this->getEndPoint(false);
        $this->setEndPoint($relativePoint, $absolutePoint);
        
        unset($this->attributes);
    }

    public function getEndPoint($absolute = true): array
    {
        if ($absolute && $this->type === 'absolute') {
            if (empty($this->prev)) {
                return [
                    'x' => $this->coordinate['x'],
                    'y' => 0,
                ];
            }
            $prevPoint = $this->prev->getEndPoint();
            return [
                'x' => $this->coordinate['x'],
                'y' => $prevPoint['y'],
            ];
        }
        if ($absolute && $this->type === 'relative') {
            if (empty($this->prev)) {
                return [
                    'x' => $this->coordinate['x'],
                    'y' => 0,
                ];
            }
            $prevPoint = $this->prev->getEndPoint();
            return [
                'x' => $prevPoint['x'] + $this->coordinate['x'],
                'y' => $prevPoint['y'],
            ];
        }
        if (!$absolute && $this->type === 'absolute') {
            if (empty($this->prev)) {
                return [
                    'x' => $this->coordinate['x'],
                    'y' => 0,
                ];
            }
            $prevPoint = $this->prev->getEndPoint();
            return [
                'x' => $this->coordinate['x'] - $prevPoint['x'],
                'y' => 0,
            ];
        }
        if (!$absolute && $this->type === 'relative') {
            return [
                'x' => $this->coordinate['x'],
                'y' => 0,
            ];
        }
    }
}