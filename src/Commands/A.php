<?php

declare(strict_types=1);

namespace BladeUI\Icons\Commands;

use Error;

class A extends Command
{
    protected $nextPoint = 0;

    protected $count = 0;

    public function initialization()
    {
        if (count($this->attributes) % 3 > 0) {
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
        $relativePoint = $this->getEndPoint(false);
        $this->setEndPoint($relativePoint, $absolutePoint);
        unset($this->attributes);
    }

    public function getEndPoint($absolute = true)
    {
        $n = $this->count-1;
        return $this->getPoint($n, $absolute);
    }

    public function getPoint($n = null, $absolute = true)
    {
        if($n >= $this->count){
            throw new Error("Point doesn't exist, max position: " . $this->count, 1);
        }
        if($n === null){
            $n = $this->nextPoint;
            if($this->nextPoint >= $this->count){
                $this->nextPoint = 0;
            }else{
                $this->nextPoint++;
            }
        }
        if($absolute && $this->type === 'absolute'){
            return [
                'x' => $this->coordinates[$n]['x'],
                'y' => $this->coordinates[$n]['y'],
            ];
        }
        if($absolute && $this->type === 'relative'){
            if(empty($this->prev)){
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
        if(!$absolute && $this->type === 'absolute'){
            if(empty($this->prev)){
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
        if(!$absolute && $this->type === 'relative'){
            return [
                'x' => $this->coordinates[$n]['x'],
                'y' => $this->coordinates[$n]['y'],
            ];
        } 
    }

    public function resetNext()
    {
        $this->nextPoint = 0;
    }

    public function getCenter($n = null)
    {
        if($n >= $this->count){
            throw new Error("Point doesn't exist, max position: " . $this->count, 1);
        }

        if($n === null){
            $n = $this->nextPoint;
            if($this->nextPoint >= $this->count){
                $this->nextPoint = 0;
            }else{
                $this->nextPoint++;
            }
        }

        if($n < 1){
            $aPoint = $this->prev->getEndPoint();
        }else{
            $aPoint = $this->getPoint($n-1);
        }
        
        $bPoint =$this->getPoint($n);

        $angle = $this->coordinates[$n]['xRotation'] * 180 / pi();

        if($angle > 0){
            $aPoint = [
                'x' => $aPoint['x'] * cos($angle) + $aPoint['y'] * sin($angle),
                'y' => $aPoint['y'] * cos($angle) - $aPoint['x'] * sin($angle),
            ];

            $bPoint = [
                'x' => $bPoint['x'] * cos($angle) + $bPoint['y'] * sin($angle),
                'y' => $bPoint['y'] * cos($angle) - $bPoint['x'] * sin($angle),
            ];
        }

        $arcRatio = (pow($this->coordinates[$n]['rx'],2)/pow($this->coordinates[$n]['ry'],2));
        $dx = ($aPoint['x'] - $bPoint['x']);
        $dy = ($aPoint['y'] - $bPoint['y']);

        $h = (($arcRatio * $dy) + pow($aPoint['x'],2) - pow($bPoint['x'],2))/(2 * $dx);
        $k = $bPoint['y'] + pow($this->coordinates[$n]['rx'],2) + ($arcRatio * pow(($bPoint['x'] - $h),2));

        return [
            'x' => $h,
            'y' => $k
        ];
        
    }
}
