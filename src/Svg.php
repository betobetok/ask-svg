<?php

declare(strict_types=1);

namespace BladeUI\Icons;

use BladeUI\Icons\Configurators\Style;

/**
 * Svg
 */
final class Svg extends SvgElement
{
    /** @var Style $style */
    public Style $style;

<<<<<<< Updated upstream
    private array $elements = [];

    public function __construct(string $name, string $contents, array $attributes = [])
=======
    /**
     * 
     *
     * @param  string $fileName
     * @param  string $contents
     * @param  array $attributes
     * @param  mixed $context
     * @return void
     */
    public function __construct(string $fileName, string $contents, array $attributes = [], $context = null)
>>>>>>> Stashed changes
    {
        $name = explode('/', $fileName);
        $this->id($fileName);
        $name = $name[count($name) - 1];
        $this->contents = $contents;

        $styleContent = $this->getStylefromContent();
        $this->style = new Style($styleContent, $name);
        $this->removeStylefromContent();

        $svg = preg_match("/<svg[^>]*>/i", $contents, $svgTag);

        if ($svg !== 0 && $svg !== false && $attributes === []) {
            $attributesFromString = $this->getElementAttributes($svgTag[0]);
            $attributes = array_merge($attributes, $attributesFromString);
            $contents = str_replace([$svgTag[0], '</svg>'], '', $contents);
        }

        unset($attributes['id']);

        foreach ($attributes as $key => $attribute) {
            $this->$key($attribute);
        }

        $contents = $this->replaceClasses($this->style, $contents);
        parent::__construct('svg', $contents);

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
     * @param  mixed $style
     * @return self
     */
    public function setStyle($style): self
    {
        $this->style = $style;
        return $this;
    }

    /**
     * replaceClasses
     *
     * @param  mixed $style
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
     * @param  Svg $param
     * @return Svg
     */
    public function mergeSvgs(Svg ...$param): Svg
    {
        if (is_array($param[0])) {
            $param = $param[0];
        }

        $old = $this->getAllSvgElements($this);
        $name = $old['attributes']['id'] ?? '';
        
        // $content = $this->findGroupElement($this->contents(), 'svg') !== [] ? "\n<g" . (isset($old['attributes']['id']) ? 'id="' . $old['attributes']['id'] . '"' : '') . '>' . "\n" . $this->findGroupElement($this->contents(), 'svg')[0]->contents() . "\n</g>" : '';
        foreach ($param as $svg) {
            $new = $this->getAllSvgElements($svg);
            $newElements = array_keys($new);
            $old['style']->setClasses(array_merge($old['style']->classes(), $new['style']->classes()));
            $attributes = array_merge($old['style']->attributes(), $new['style']->attributes());
            foreach ($attributes as $name => $attribute) {
                $old['style']->$name($attribute);
            }
            $svg->removeSvgAttribute();
            $tmp = new SvgElement('g', '', $svg->attributes);
            if(isset($svg->attributes['id'])){
                $tmp->id($svg->attributes['id']);
                $svg->removeId();
            }
            foreach ($newElements as $element) {
                if ($element === 'style' || $element === 'contents' || $element === 'elements') {
                    unset($new[$element]);
                    continue;
                }
                if ($element === 'attributes') {
                    foreach($new[$element] as $k => $att){
                        $k = str_replace('"', '', $k);
                        $att = str_replace('"', '', $att);
                        $tmp->$k($att);
                    }
                    continue;
                }
                $tmp->$element = $new[$element];
            }
            $tmp->removeSvgAttribute();
            foreach ($svg->attributes() as $name => $attribute) {
                $svg->$name(str_replace('"', '', $attribute));
            }
            $name .= isset($new['attributes']['id']) ? '-' . $new['attributes']['id'] : '';
            $this->g = array_merge([$tmp], $this->g??[]) ;

        }
        $this->style = $old['style'];
        $this->setName('merge-' . $name);

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
}
