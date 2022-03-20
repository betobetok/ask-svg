<?php

declare(strict_types=1);

namespace ASK\Svg\Configurators;

/**
 * A LinearGradient element in a svg document
 */
class LinearGradient extends Configurator
{
    public function __construct(string $contents, array $attributes = [], $context = null)
    {

        foreach ($attributes as $key => $attribute) {
            $this->$key($attribute);
        }

        parent::__construct($contents,  $attributes, $context);
        $this->name = 'linearGradient';
    }

    /** (overloaded Method from SvgElement) */
    public function toHtml(): string
    {
        if (isset($this->stop)) {
            return sprintf('<' . $this->name() . '%s', $this->renderAttributes()) . '>' . $this->contents() . '</' . $this->name() . '>';
        } else {
            return sprintf('<' . $this->name() . '%s', $this->renderAttributes()) . '/>';
        }
    }
}
