<?php

declare(strict_types=1);

namespace BladeUI\Icons\Configurators;

use BladeUI\Icons\Concerns\RendersAttributes;
use BladeUI\Icons\Configurators\Configurator;
use BladeUI\Icons\SvgElement;

/**
 * Style
 */
class Style extends Configurator
{
    /** @var array $classes */
    private $classes;

    /**
     * 
     *
     * @param string svgContent
     * @param string name
     * @param SvgElement context
     *
     * @return void
     */
    public function __construct(string $svgContent, string $name, SvgElement $context = null)
    {
        $contents = $this->configAttributesAndContent($name, $svgContent, []);
        parent::__construct($contents, [], $context);
        $this->renameStyle($name);
        $this->removeContents();
    }

    /**
     * renameStyle
     *
     * @param  mixed $svgElementName
     * @return self
     */
    public function renameStyle(string $svgElementName): self
    {
        $this->classes = [];
        preg_match_all("/.([a-z0-9]*)({[^}]*})/i", $this->contents(), $comands);
        foreach ($comands[1] as $k => $class) {
            $className = $class . '-' . $svgElementName;
            $this->classes[$className] = $comands[2][$k];
        }
        return $this;
    }

    /**
     * classes get the css classes from style
     *
     * @return array
     */
    public function classes(): array
    {
        return $this->classes;
    }

    /**
     * setClasses set the css classes for style
     *
     * @param  array $classes
     * @return self
     */
    public function setClasses(array $classes): self
    {
        $this->classes = $classes;
        return $this;
    }

    /**
     * toHtml
     *
     * @return string
     */
    public function toHtml(): string
    {
        $ret = '<style' . sprintf('%s', $this->renderAttributes()) . ' >' . "\n";
        $classes = $this->classes();
        foreach ($classes as $className => $comands) {
            $ret .= '.' . $className . ' ' . $comands . "\n";
        }
        $ret .= '</style>';
        return $ret;
    }

    /**
     * mergeStyles Merge one Style with an other
     *
     * @param  Style $add
     * @return self|null
     */
    public function mergeStyles(Style $add): ?self
    {
        if (empty($this->attributes())) {
            if (empty($add->attributes())) {
                return $this;
            }
            foreach ($add->attributes() as $name => $arguments) {
                $this->setAttribute($name, $arguments);
            }
        }

        if (empty($add->attributes())) {
            return $this;
        }
        $array = array_merge($this->attributes(), $add->attributes());
        foreach ($array as $name => $arguments) {
            $this->setAttribute($name, $arguments);
        }

        return $this;
    }

    /**
     * toArray
     *
     * @return void
     */
    public function toArray()
    {
        $ret = parent::toArray();
        $ret['classes'] = $this->classes();
        return $ret;
    }
}
