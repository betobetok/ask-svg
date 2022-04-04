<?php

declare(strict_types=1);

namespace ASK\Svg\Shapes;

use ASK\Svg\Configurators\uSvgElement;
use ASK\Svg\SvgElement;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use NumPHP\Core\NumArray;

/**
 * An element that make a Shape in a svg document
 *
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
     * get the Start position o the element
     *
     * @return void
     */
    public function getStartPosition()
    {
        return $this->startPosition;
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

            if ($attribute === 'points') {
                return sprintf('%s="%s"', Str::snake($attribute, '-'), $this->pointsString());
            }

            return sprintf('%s="%s"', STR::snake($attribute, '-'), $value);
        })->implode(' ');
    }

    /**
     * (overloaded Method from SvgElement)
     *
     * @return string
     */
    public function toHtml(): string
    {
        return sprintf('<%s %s/>', $this->name(), $this->renderAttributes());
    }

    public function attributes(): array
    {
        $thisAttributes = Arr::except(get_object_vars($this), array_keys(get_object_vars(new uSvgElement('', '', ['transform' => '']))));
        $thisAttributes = Arr::except($thisAttributes, ['startPosition']);
        return array_merge(parent::attributes(), $thisAttributes);
    }
}
