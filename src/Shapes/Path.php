<?php

declare(strict_types=1);

namespace ASK\Svg\Shapes;

use ASK\Svg\SvgElement;
use Exception;

/**
 * A Path element in a svg document
 */
class Path extends Shape
{
    /** @var string  $dString*/
    private $dString = '';

    /** @var array d*/
    protected array $d = [];

    public function __construct(string $contents, array $attributes = [], SvgElement $context = null)
    {
        parent::__construct($contents,  $attributes, $context);

        if (isset($this->attributes()['d']) && !empty($this->attributes()['d'])) {
            $this->dString = $this->attributes()['d'];
        }
        $this->d = $this->getExistingComands($this->dString);
        $this->removeAtt('d');

        if (!empty($this->d)) {
            $this->startPosition = $this->d[0][array_key_first($this->d[0])]->endPointAbs;
        }
        unset($this->dString);
    }

    /**
     * (overloaded Method from SvgElement)
     *
     * @return string
     */
    public function toHtml(): string
    {
        $dString = $this->content();
        return sprintf('<%s d="%s" %s/>', $this->name(), $dString, $this->renderAttributes());
    }

    /**
     * content get the content string of the svg elemnt to print in a HTML document
     *
     * @return string
     */
    public function content(): string
    {
        $content = '';
        foreach ($this->d as $comand) {
            foreach ($comand as $name => $confg) {
                $content .= $confg->toHtml();
            }
        }
        return $content;
    }

    /**
     * getExistingComands get the existing comands in a d attribute from a string
     *
     * @param  string $d
     * @return array
     */
    public function getExistingComands(string $d): array
    {
        $commands = [];
        $prev = null;
        preg_match_all('/([a-zA-Z]{1})\s?([e0-9\s,.-]+)?[^A-Za-z]?/', $d, $match);
        foreach ($match[1] as $k => $name) {
            preg_match_all('/(-?[0-9.]+(e-\d+)?)/', $match[2][$k], $arguments);
            $commandClass = 'BladeUI\\Icons\\Commands\\' . ucfirst($name);
            $type = $name === strtolower($name) ? 'relative' : 'absolute';
            if (class_exists($commandClass)) {
                $command = new $commandClass($type, $arguments[0], $prev);
                $prev = $command;
            }
            $commands[$k][$name] = $command ?? $commandClass;
        }
        return $commands;
    }

    /**
     * renderAttributes return a string with attributes in a HTML format
     * (overloaded Method from RenderAttributes)
     *
     * @return string
     */
    protected function renderAttributes(): string
    {
        if (count($this->attributes()) == 0) {
            return '';
        }

        return ' ' . collect($this->attributes())->map(function (string $value, $attribute) {
            if (is_int($attribute)) {
                return $value;
            }

            return sprintf('%s="%s"', $attribute, $value);
        })->implode(' ');
    }
}
