<?php

declare(strict_types=1);

namespace BladeUI\Icons\Shapes;

use BladeUI\Icons\SvgElement;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use NumPHP\Core\NumArray;

/**
<<<<<<< Updated upstream
 * Shape
=======
 * An element that make a Shape in a svg document
 * 
>>>>>>> Stashed changes
 */
abstract class Shape extends SvgElement
{

    /** @var NumArray $startPosition */
    protected NumArray $startPosition;

    public function __construct(string $contents, array $attributes = [], SvgElement $context = null)
    {
        $name = explode('\\', get_class($this));
        $name = strtolower($name[array_key_last($name)]);

        $this->isTransformable = true;

        $contents = $this->configAttributesAndContent($name, $contents, $attributes);

        parent::__construct($name,  $contents,  $attributes, $context);
    }
    /**
     * getStartPosition
     *
     * @return void
     */
    public function getStartPosition()
    {
        return $this->startPosition;
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

        $thisAttributes = Arr::except(get_object_vars($this), array_keys(get_object_vars(new SvgElement('', '', ['transform' => '']))));

        return ' ' . collect($this->attributes())->map(function (string $value, $attribute) {
            if (is_int($attribute)) {
                return $value;
            }

            return sprintf('%s="%s"', STR::snake($attribute, '-'), $value);
        })->implode(' ') . ' ' . collect($thisAttributes)->map(function ($value, $attribute) {
            return sprintf('%s="%s"', STR::snake($attribute, '-'), $value);
        })->implode(' ');
    }

    /**
     * toHtml
     *
     * @return string
     */
    public function toHtml(): string
    {
        return sprintf('<%s %s/>', $this->name(), $this->renderAttributes());
    }
}
