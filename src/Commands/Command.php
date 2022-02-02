<?php

declare(strict_types=1);

namespace BladeUI\Icons\Commands;

abstract class Command
{
    private string $type;

    private string $comand;

    private array $attributes;

    private Command $prev;

    private int $position;

    public function __construct(string $type, array $attributes = [], ?Command $prev = null)
    {
        $this->type = $type;
        if(!empty($prev)){
            $this->prev = $prev;
        }
        $this->attributes = $attributes;
        $this->inisializate();
    }

    public function inisializate()
    {
        # code...
    }

}