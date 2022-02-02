<?php

declare(strict_types=1);

namespace BladeUI\Icons;

use BladeUI\Icons\Concerns\RendersAttributes;
use Exception;
use Illuminate\Contracts\Support\Htmlable;

class Path
{
    

    public function __construct(string $id, string $d, array $attributes = [])
    {
        $this->id = $id;
        $this->d = $d;
        foreach ($attributes as $key => $attribute) {
            $this->$key($attribute);
        }
    }
}
