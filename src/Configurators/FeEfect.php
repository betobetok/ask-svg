<?php

declare(strict_types=1);

namespace ASK\Svg\Configurators;

use ASK\Svg\SvgElement;
use Illuminate\Support\Str;

/**
 * a Filter efect used in a definitions element to define a filter
 */
class FeEfect extends Configurator
{
    public function __construct(string $name, string $contents, array $attributes = [], $context = null)
    {
        foreach ($attributes as $key => $attribute) {
            $this->$key($attribute);
        }
        SvgElement::__construct('fe' . $name, '',  $attributes, $context);
    }

    /**
     * renderAttributes return a string with attributes in a HTML format
     * (overloaded Method from RenderAttributes)
     *
     * @return string
     */
    protected function renderAttributes(): string
    {
        if (count($this->attributes()) == 0) {
            return '';
        }

        return ' ' . collect($this->attributes())->map(function (string $value, $attribute) {
            if (is_int($attribute)) {
                return $value;
            }

            return sprintf('%s="%s"', STR::snake($attribute, '-'), $value);
        })->implode(' ');
    }
}
