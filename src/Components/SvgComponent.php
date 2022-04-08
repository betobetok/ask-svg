<?php

declare(strict_types=1);

namespace ASK\Svg\Components;

use Closure;
use Illuminate\View\Component;

/**
 * Svg
 */
class SvgComponent extends Component
{
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

            return svg($this->componentName, $class, $attributes)->toHtml();
        };
    }
}
