<?php

declare(strict_types=1);

namespace BladeUI\Icons\Shapes;

use BladeUI\Icons\Commands as Comands;

trait Shape
{

    protected array $startPosition;

    protected array $endPosition;

    protected array $commands;

    public function getStartPosition()
    {
        return $this->startPosition;
    }

    public function getComandsFromPath(string $d)
    {
    }

    public function getExistingComands(string $d)
    {
        $commands = [];
        $prev = null;
        preg_match_all('/([a-zA-Z]{1})\s?([e0-9\s,.-]+)?[^A-Za-z]/', $d, $match);
        $i=0;
        foreach($match[1] as $k => $name){
            preg_match_all('/([0-9.e-]+)/',$match[2][$k], $arguments);
            $commandClass = 'BladeUI\\Icons\\Commands\\'. ucfirst($name);
            $type = $name === strtolower($name)?'relative':'absolute';
            if(class_exists($commandClass)){
                $command = new $commandClass($type, $arguments[0], $prev);
                $prev = $command;
            }
            $commands[$k][$name] = $command ?? $commandClass;
        }
        return $commands;
    }
}
