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
=======
/**
 * Icon
 */
>>>>>>> Stashed changes
final class Icon extends Component
{
    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

<<<<<<< Updated upstream
=======
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

            return svg($this->name, $class, $attributes)->toHtml();
        };
    }
}
