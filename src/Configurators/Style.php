<?php

declare(strict_types=1);

namespace ASK\Svg\Configurators;

use ASK\Svg\Configurators\Configurator;
use ASK\Svg\Conteiner;
use ASK\Svg\SvgElement;

/**
 * The Style element in a svg document
 */
class Style extends Configurator implements Conteiner
{
    /** @var array $classes */
    private $classes;

    /** @var array $classes */
    private $rules;

    public function __construct(string $svgContent, array $attributes = [], SvgElement $context = null)
    {
        $name = '';
        if ($context !== null) {
            $name = $context->id();
        }
        $contents = $this->configAttributesAndContent('style', $svgContent, []);

        parent::__construct($contents, [], $context);
        $this->makeRules();
        $this->renameClasses($name);
        $this->removeContents();
        unset($this->elements);
    }

    /**
     * renameClasses agregate the name of the svg at ende of the classes neme 
     * to avoid a name conflict between svgs in a merge
     *
     * @param  string $svgElementName
     * @return self
     */
    public function renameClasses(string $svgElementName): self
    {
        $this->classes = [];
        $this->rules = [];
        foreach ($this->rules as $selector => $declarations) {
            if (strpos($selector, '.') === 0) {
                $className = $selector . '-' . $svgElementName;
                $this->classes[$className] = $declarations;
                $this->rules[$className] = $declarations;
                unset($this->rules[$selector]);
            }
        }
        return $this;
    }

    /**
     * makeRules create an array of roules from the string content
     *
     * @return void
     */
    public function makeRules()
    {
        $this->rules = [];
        preg_match_all("/([.#a-z0-9-]*)\s?({[^}]*})/i", $this->contents(), $comands);
        foreach ($comands[1] as $k => $selector) {
            preg_match_all("/([a-z0-9-]*):([^;]*);/i", $comands[2][$k], $declarations);
            $this->rules[$selector] = [];
            foreach ($declarations[1] as $i => $property) {
                $this->rules[$selector][$property] = $declarations[2][$i];
            }
        }
    }

    /**
     * get the css classes from style
     *
     * @return array
     */
    public function classes(): array
    {
        return $this->classes;
    }

    /**
     *  get the css rules from style or set rules for the style element
     * 
     * * if it's called without arguments, return the rules array
     * * if it's called with arguments, set the rules gived in the arguments array
     * 
     * a valid roules array look like thisone:
     * [
     * selector1 => [
     *      property1 => values,
     *      property2 => values,
     *      ],
     * selector2 => [
     *      property1 => values,
     *      property2 => values
     *      ]
     * ]
     * 
     * @param  array $arg
     * @return array|null
     */
    public function rules(array $arg = []): ?array
    {
        if (count($arg) <= 0) {
            return $this->rules;
        }
        foreach ($arg as $selector => $rules) {
            if (!is_array($rules)) {
                return null;
            }
            foreach ($rules as $property => $declarations) {
                if (isset($this->rules)) {
                    $this->rules[$selector][$property] = $declarations;
                } else {
                    $this->rules = [
                        $selector => [
                            $property => $declarations
                        ]
                    ];
                }
            }
        }
        return $this->rules;
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
     * (overloaded Method from SvgElement)
     *
     * @return string
     */
    public function toHtml(): string
    {
        if (empty($this->rules())) {
            return '';
        }

        $ret = sprintf("<style %s>", $this->renderAttributes());

        foreach ($this->rules() as $ruleName => $declarations) {
            $ret .=  sprintf("%s {", $ruleName);
            foreach ($declarations as $property => $value) {
                $ret .=  sprintf("%s: %s;", $property, $value);
            }
            $ret .= "}";
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
        $this->rules = array_merge($this->rules, $add->rules);

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
        $ret['rules'] = $this->rules();
        return $ret;
    }

    public function getContent()
    {
    }
    public function setContent($content)
    {
    }
}
