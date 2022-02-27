<?php

declare(strict_types=1);

namespace BladeUI\Icons\Commands;

use Error;

class M extends Command
{
    private $x;
    private $y;

    public function initialization()
    {
        if (count($this->attributes) % 2 > 0) {
            throw new Error('Incorrect configuration of attributes');
        }
        $this->x = $this->attributes[0];
        $this->y = $this->attributes[1];
        $absolutePoint = $this->getEndPoint();
        $relativePoint = $this->getEndPoint(false);
        $this->setEndPoint($relativePoint, $absolutePoint);
        $this->coordinates = $this->type === 'absolute' ? [$absolutePoint]: [$relativePoint];
        unset($this->attributes);
    }

    public function getX()
    {
        return $this->x;
    }

    public function getY()
    {
        return $this->y;
    }

    public function getEndPoint($absolute = true): array
    {
        if($absolute && $this->type === 'absolute'){
            return [
                'x' => $this->x,
                'y' => $this->y,
            ];
        }
        if($absolute && $this->type === 'relative'){
            if(empty($this->prev)){
                return [
                    'x' => $this->x,
                    'y' => $this->y,
                ];
            }
            $prevPoint = $this->prev->getEndPoint();
            return [
                'x' => $prevPoint['x'] + $this->x,
                'y' => $prevPoint['y'] + $this->y,
            ];
        }
        if(!$absolute && $this->type === 'absolute'){
            if(empty($this->prev)){
                return [
                    'x' => $this->x,
                    'y' => $this->y,
                ];
            }
            $prevPoint = $this->prev->getEndPoint();
            return [
                'x' => $this->x - $prevPoint['x'],
                'y' => $this->y - $prevPoint['y'],
            ];
        }
        if(!$absolute && $this->type === 'relative'){
            return [
                'x' => $this->x,
                'y' => $this->y,
            ];
        }
        
    }

    public function getDinstance($toPoint = [])
    {
        if(empty($toPoint)){
            $toPoit = [
                'x' => 0,
                'y' => 0,
            ];
        }
        $dx = $this->x - $toPoint['x'];
        $dy = $this->y - $toPoint['y'];
        $distance = sqrt(pow($dx,2)+(pow($dy,2)));
        return [
            'dx' => $dx,
            'dy' => $dy,
            'distance' => $distance
        ];      
    }
    
}