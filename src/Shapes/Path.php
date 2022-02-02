<?php

declare(strict_types=1);

namespace BladeUI\Icons\Shapes;

use BladeUI\Icons\Concerns\RendersAttributes;
use BladeUI\Icons\SvgElement;
use Exception;
use Illuminate\Contracts\Support\Htmlable;

class Path extends SvgElement
{
    use Shape;

    private string $d;

    public function __construct(string $name, string $contents, array $attributes = [])
    {
        parent::__costruct( $name,  $contents,  $attributes);
        preg_match('/d="([A-Za-z0-9\s.,-]+)"/', $contents, $d);
       
        foreach ($attributes as $key => $attribute) {
            $this->$key($attribute);
        }
        $this->d = $d[1];
        if(empty($d)){
            throw new Exception("Path had no d", 1);
        }
        
        dd($this->getExistingComands($this->d));
    }
}
