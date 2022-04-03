<?php

declare(strict_types=1);

namespace ASK\Svg\Concerns;

use Illuminate\Support\Str;

/**
 *@trait RendersAttributes
 */
trait RendersAttributes
{
    /** @var array $attributes */
    private array $attributes = [];

    /**
     * get attributes
     *
     * @return array
     */
    public function attributes(): array
    {
        return $this->attributes;
    }

    /**
     * renderAttributes return a string with attributes in a HTML format
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

            return sprintf('%s="%s"', $attribute, $value);
        })->implode(' ');
    }

    public function __call(string $method, array $arguments): self
    {
        if (count($arguments) === 0) {
            $this->attributes[] = Str::camel($method);
        } else {
            $this->attributes[Str::camel($method)] = $arguments[0];
        }
        return $this;
    }

    /**
     * removeAtt remove an Attribute from the attributes list
     *
     * @param  string $attribute
     * @return void
     */
    public function removeAtt(string $attribute)
    {
        if (isset($this->attributes[$attribute])) {
            unset($this->attributes[$attribute]);
        }
    }

    /**
     * setAttribute set an attribute with the own value in the Attributes list
     *
     * @param  string $name
     * @param  mixed $arguments
     * @return void
     */
    public function setAttribute(string $name, $arguments = true)
    {
        $arguments = is_string($arguments) ? $arguments : (is_bool($arguments) ? ($arguments ? 'true' : 'false') : (string)$arguments);
        $this->attributes[Str::snake($name, '-')] = $arguments;
    }

    /**
     * removeAllAttributes remove all attributes from the attributes list
     *
     * @return void
     */
    public function removeAllAttributes()
    {
        $this->attributes = [];
    }
}
