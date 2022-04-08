<?php

declare(strict_types=1);

namespace ASK\Svg\Configurators;

use ASK\Svg\Conteiner;

/**
 * A RadialGradient element in a svg document
 */
class RadialGradient extends Configurator implements Conteiner
{
    public function __construct(string $contents, array $attributes = [], $context = null)
    {
        foreach ($attributes as $key => $attribute) {
            $this->$key($attribute);
        }

        parent::__construct($contents,  $attributes, $context);
        $this->name = 'radialGradient';
    }

    /** (overloaded Method from SvgElement) */
    public function toHtml(): string
    {
        if (isset($this->stop)) {
            return sprintf('<%s %s>' . NEW_LINE . TAB . '%s' . NEW_LINE . '</%s>', $this->name(), $this->renderAttributes(), $this->contents(), $this->name());
        } else {
            return sprintf('<%s %s />' . $this->name(), $this->renderAttributes());
        }
    }

    public function getContent()
    {
    }
    public function setContent($content)
    {
    }
}
