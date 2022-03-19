<?php

declare(strict_types=1);

namespace BladeUI\Icons\Configurators;

use BladeUI\Icons\SvgElement;
use Illuminate\Support\Str;

/**
 * Defs
 */
class FeEfect extends Configurator
{
    public function __construct(string $name, string $contents, array $attributes = [], $context = null)
    {
        foreach ($attributes as $key => $attribute) {
            $this->$key($attribute);
        }
        SvgElement::__construct('fe'.$name, '',  $attributes, $context);
    }
        
    /**
     * renderAttributes
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
