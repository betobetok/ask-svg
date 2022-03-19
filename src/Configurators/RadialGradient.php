<?php

declare(strict_types=1);

namespace BladeUI\Icons\Configurators;

/**
 * RadialGradient
 */
class RadialGradient extends Configurator
{
    /**
     *
     * @param string name
     * @param string contents
     * @param array attributes
     * @param SvgElement context
     *
     * @return void
     */
    public function __construct(string $contents, array $attributes = [], $context = null)
    {
        foreach ($attributes as $key => $attribute) {
            $this->$key($attribute);
        }

        parent::__construct($contents,  $attributes, $context);
        $this->name = 'radialGradient';
    }

    public function toHtml(): string
    {
        if (isset($this->stop)) {
            return sprintf('<' . $this->name() . '%s', $this->renderAttributes()) . '>' . $this->contents() . '</' . $this->name() . '>';
        } else {
            return sprintf('<' . $this->name() . '%s', $this->renderAttributes()) . '/>';
        }
    }
}
