<?php

declare(strict_types=1);

<<<<<<< Updated upstream
namespace BladeUI\Icons\Components;
=======
namespace ASK\Svg\Components;
>>>>>>> Stashed changes

use Closure;
use Illuminate\View\Component;

<<<<<<< Updated upstream
final class Svg extends Component
{
=======
/**
 * Svg
 */
final class Svg extends Component
{
    /**
     * render
     *
     * @return Closure
     */
>>>>>>> Stashed changes
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
