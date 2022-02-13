<?php

declare(strict_types=1);

namespace BladeUI\Icons\Shapes;

use BladeUI\Icons\Concerns\RendersAttributes;
use BladeUI\Icons\SvgElement;
use Exception;
use Illuminate\Contracts\Support\Htmlable;

class Circle extends SvgElement
{
    use Shape;

    use RendersAttributes;

    protected float $cx;

    protected float $cy;

    protected float $r;

    public function __construct(string $name, string $contents, array $attributes = [], $context = null)
    {
        parent::__construct($name,  $contents,  $attributes, $context);

        // foreach ($attributes as $key => $attribute) {
        //     $this->$key($attribute);
        // }
    }

    public function toHtml(): string
    {
        return sprintf('<%s d="%s" %s/>', $this->name(), $this->dString, $this->renderAttributes());    
    }

    public function getCenter()
    {
        $att = $this->attributes();
        return [
            'x' => (float)$att['cx'],
            'y' => (float)$att['cy'],
        ];
    }

    public function getdiameter()
    {
        $att = $this->attributes();
        return (float)$att['r'] * 2;
    }

    public function getRadio()
    {
        $att = $this->attributes();
        return (float)$att['r'];
    }

    public function getArea()
    {
        $att = $this->attributes();
        return pi() * pow((float)$att['r'] , 2);
    }
}
