<?php

declare(strict_types=1);

namespace BladeUI\Icons;

use BladeUI\Icons\Configurators\G;
use BladeUI\Icons\Configurators\Style;

/**
 * Svg
 */
final class Svg extends SvgElement implements Conteiner
{
    /** @var Style $style */
    public Style $style;

    /**
     * 
     *
     * @param  string $fileName
     * @param  string $contents
     * @param  array $attributes
     * @return void
     */
    public function __construct(string $fileName, string $contents, array $attributes = [])
    {
        $name = explode('/', $fileName);
        $this->id($fileName);
        $name = $name[count($name) - 1];
        $this->contents = $contents;

        $styleContent = $this->getStylefromContent();
        $this->style = new Style($styleContent, $name, $this);
        $this->removeStylefromContent();

        $contents = $this->configAttributesAndContent('svg', $contents, $attributes);

        $contents = $this->replaceClasses($this->style, $contents);

        parent::__construct('svg', $contents, [], $this);

        $this->cleanContent();
    }




    /**
     * style
     *
     * @return Style
     */
    public function style(): Style
    {
        return $this->style;
    }

    /**
     * getSVGAtributes
     *
     * @return array
     */
    public function getSVGAtributes(): array
    {
        $svg = $this->getElements('svg');
        if (isset($svg[0])) {
            return $svg[0]->attributes();
        }
        return [];
    }

    /**
     * setStyle
     *
     * @param  Style $style
     * @return self
     */
    public function setStyle(Style $style): self
    {
        $this->style = $style;
        return $this;
    }

    /**
     * replaceClasses
     *
     * @param  Style $style
     * @param  string $content
     * @return string
     */
    public function replaceClasses(Style $style, string $content): string
    {
        foreach ($style->classes() as $className => $comands) {
            $class = explode('-', $className)[0];
            $content = str_replace($class, $className, $content);
        }
        return $content;
    }

    /**
     * mergeSvgs
     *
     * @param  Svg[] $param
     * @return Svg
     */
    public function mergeSvgs(...$param): Svg
    {
        if (is_array($param[0])) {
            $param = $param[0];
        }

        $old = $this->getAllSvgElements($this);
        $name = $old['attributes']['id'] ?? '';

        foreach ($param as $svg) {
            $new = $this->getAllSvgElements($svg);
            $newElements = array_keys($new);
            $old['style']->setClasses(array_merge($old['style']->classes(), $new['style']->classes()));
            $attributes = array_merge($old['style']->attributes(), $new['style']->attributes());

            foreach ($attributes as $name => $attribute) {
                $old['style']->$name($attribute);
            }
            $svg->removeSvgAttribute();
            $tmpG = new G('', $svg->attributes, $this);
            if (isset($svg->attributes['id'])) {
                $tmpG->id($svg->attributes['id']);
                $svg->removeId();
            }

            foreach ($newElements as $element) {
                if ($element === 'style' || $element === 'contents' || $element === 'elements' || $element === 'name' || $element === 'context') {
                    unset($new[$element]);
                    continue;
                }
                if ($element === 'attributes') {
                    foreach ($new[$element] as $k => $att) {
                        $k = str_replace('"', '', $k);
                        $att = str_replace('"', '', $att);
                        $tmpG->$k($att);
                    }
                    continue;
                }
                $tmpG->$element = $new[$element];
            }

            $tmpG->removeSvgAttribute();
            foreach ($svg->attributes() as $name => $attribute) {
                $svg->$name(str_replace('"', '', $attribute));
            }
            $this->g = array_merge([$tmpG], $this->g ?? []);
        }
        $this->style = clone ($old['style']);
        unset($old['style']);
        return $this;
    }

    public function toHtml(): string
    {
        return '<svg' . sprintf('%s', $this->renderAttributes()) . ' >' . "\n" . $this->contents() . "\n" . '</svg>';
    }

    /**
     * getAllSvgElements
     *
     * @param  mixed $svg
     * @return array
     */
    public function getAllSvgElements(Svg $svg): array
    {
        $elements = get_object_vars($svg);
        $ret = [];
        foreach ($elements as $name => $element) {
            $ret[$name] = $element;
        }
        return $ret;
    }

    /**
     * cleanContent
     *
     * @return self
     */
    public function cleanContent(): self
    {
        $elements = $this->getAllSvgElements($this);
        foreach ($elements as $element) {
            if (is_array($element)) {
                foreach ($element as $svg) {
                    if ($svg instanceof SvgElement) {
                        $svg->removeContents();
                    }
                }
            }
        }
        $this->removeContents();
        return $this;
    }

    /**
     * Get the value of content
     */
    public function getContent()
    {
        return $this;
    }

    /**
     * Set the value of content
     *
     * @return  self
     */
    public function setContent($content)
    {
        return $this;
    }
}
