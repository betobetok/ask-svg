<?php

declare(strict_types=1);

namespace BladeUI\Icons\Configurators;

use function PHPUnit\Framework\isEmpty;

/**
 * Defs
 */
class Defs extends Configurator
{
    public function __construct(string $contents, array $attributes = [], $context = null)
    {
        foreach ($attributes as $key => $attribute) {
            $this->$key($attribute);
        }
        parent::__construct($contents,  $attributes, $context);
    }

    /**
     * mergeDefs Merge one Defs with an other
     *
     * @param  Defs[] $add
     * @return self|null
     */
    public function mergeDefs(array $add): ?self
    {
        if (is_array($add)) {
            foreach ($add as $k => $def) {
                foreach ($def->elements as $el) {
                    array_push($this->elements, $el);
                    $elementClassName = strtolower(get_class($el));
                    if (isset($this->$elementClassName)) {
                        $this->$elementClassName[] = $el;
                    } else {
                        $this->$elementClassName = [$el];
                    }
                }
                $array = array_merge($this->attributes(), $def->attributes());
                foreach ($array as $name => $arguments) {
                    $this->setAttribute($name, $arguments);
                }
            }
        }

        return $this;
    }

    public function toHtml(): string
    {
        if (!empty($this->elements)) {
            return parent::toHtml();
        } else {
            return '';
        }
    }
}
