<?php

declare(strict_types=1);

namespace ASK\Svg\Components;

use Closure;
use Illuminate\View\Component;

/**
 * Icon
 */
final class Icon extends Component
{
    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * render
     *
     * @return Closure
     */
    public function render(): Closure
    {
        return function (array $data) {
            $attributes = $data['attributes']->getIterator()->getArrayCopy();

            $class = $attributes['class'] ?? '';

            unset($attributes['class']);

            return svg($this->name, $class, $attributes)->toHtml();
        };
    }
}
