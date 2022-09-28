<?php

declare(strict_types=1);

namespace ASK\Svg\Shapes;

use ASK\Svg\SvgElement;
use Illuminate\Support\Str;

/**
 * A Path element in a svg document
 */
class Path extends Shape
{
    /** @var string  $dString*/
    private $dString = '';

    /** @var array d*/
    protected array $d = [];

    public function __construct(array $attributes = [], SvgElement $context = null)
    {
        parent::__construct($attributes, $context);
        if (isset($attributes['d']) && !empty($attributes['d'])) {
            $this->dString = $attributes['d'];
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
        return sprintf('<%s %s />', $this->name(), $this->renderAttributes());
    }

    /**
     * content get the content string of the svg elemnt to print in a HTML document
     *
     * @return string
     */
    public function d(): string
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
        preg_match_all('/([a-df-z]{1})\s?([e0-9\s,.-]+)?[^a-z]?/i', $d, $match);
        foreach ($match[1] as $k => $name) {
            $commandClass = 'ASK\\Svg\\DCommands\\' . ucfirst($name);
            $type = $name === strtolower($name) ? 'relative' : 'absolute';
            if (class_exists($commandClass)) {
                $command = new $commandClass($type, $match[2][$k], $prev);
                $prev = $command;
            }
            $commands[$k][$name] = $command ?? $commandClass;
        }
        return $commands;
    }

    public function attributes(): array
    {
        $attributes = parent::attributes();
        $attributes['d'] = $this->d();
        return $attributes;
    }
}
