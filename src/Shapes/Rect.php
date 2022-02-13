<?php

declare(strict_types=1);

namespace BladeUI\Icons\Shapes;

use BladeUI\Icons\Concerns\RendersAttributes;
use BladeUI\Icons\SvgElement;
use Exception;
use Illuminate\Contracts\Support\Htmlable;

class Rect extends SvgElement
{
    use Shape;

    use RendersAttributes;

    protected float $cx;

    protected float $cy;

    protected float $r;

    public function __construct(string $name, string $contents, array $attributes = [], $context = null)
    {
        parent::__construct($name,  $contents,  $attributes, $context);

        foreach ($attributes as $key => $attribute) {
            $this->$key($attribute);
        }

        $att = $this->getElementAttributes($contents);
        foreach ($att as $key => $attribute) {
            $this->$key($attribute);
        }
    }

    public function toHtml(): string
    {
        return sprintf('<%s d="%s" %s/>', $this->name(), $this->dString, $this->renderAttributes());    
    }

    public function getCenter()
    {
        $att = $this->attributes();
        return [
            'x' => (float)$att['x'] + (float)$att['width'] / 2,
            'y' => (float)$att['y'] + (float)$att['height'] / 2,
        ];
    }

    public function bBox()
    {
        $att = $this->attributes();
        return [
            'x' => (float)$att['x'],
            'y' => (float)$att['y'],
            'w' => (float)$att['width'],
            'h' => (float)$att['height'],
            'width' => (float)$att['width'],
            'height' => (float)$att['height'],
        ];
    }

    public function getArea()
    {
        $att = $this->attributes();
        return (float)$att['x'] * (float)$att['y'];
    }
}
