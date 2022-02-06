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

    use RendersAttributes;

    private string $dString;

    protected array $d;

    public function __construct(string $name, string $contents, array $attributes = [])
    {
        parent::__construct($name,  $contents,  $attributes);
        preg_match('/d="([A-Za-z0-9\s.,-]+)"/', $contents, $d);
       
        foreach ($attributes as $key => $attribute) {
            $this->$key($attribute);
        }
        $this->dString = $d[1];
        if(empty($d)){
            throw new Exception("Path had no d", 1);
        }
        $this->d = $this->getExistingComands($this->dString);
        if(is_array($this->d) && !empty($this->d)){
            $this->removeAtt('d');
        }
    }

    public function toHtml(): string
    {
        return sprintf('<%s d="%s" %s/>', $this->name(), $this->dString, $this->renderAttributes());    
    }
}
