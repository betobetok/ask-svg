<?php

declare(strict_types=1);

namespace ASK\Svg\Concerns;

use Illuminate\Support\Str;

trait RendersAttributes
{
    private array $attributes = [];

    public function attributes(): array
    {
        return $this->attributes;
    }

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

    public function removeAtt(string $attribute)
    {
        if (isset($this->attributes[$attribute])) {
            unset($this->attributes[$attribute]);
        }
    }

    public function setAttribute(string $name, string $arguments)
    {
        $this->attributes[Str::snake($name, '-')] = $arguments;
    }

    public function removeAllAttributes()
    {
        $this->attributes = [];
    }
}
