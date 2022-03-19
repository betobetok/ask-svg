<?php

declare(strict_types=1);

namespace BladeUI\Icons;

use BladeUI\Icons\Configurators\Defs;
use BladeUI\Icons\Configurators\G;
use BladeUI\Icons\Configurators\Style;
use Illuminate\Support\Arr;

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
        $this->style = new Style($styleContent, [], $this);
        $this->removeStylefromContent();

        $this->contents = $this->configAttributesAndContent('svg', $this->contents, $attributes);

        $this->contents = $this->replaceClasses($this->style, $this->contents);

        parent::__construct('svg', $this->contents);

        $this->cleanContent();
    }

    /**
     * getStylefromContent
     *
     * @return string
     */
    public function getStylefromContent(): string
    {
        preg_match("/(<style[^>]*>)[^á]*<\/style>/i", $this->contents(), $match);
        return $match[0] ?? '';
    }

    /**
     * getDefsfromContent
     *
     * @return string
     */
    public function getDefsfromContent(): string
    {
        preg_match("/(<defs[^>]*>)[^á]*<\/defs>/i", $this->contents(), $match);
        return $match[0] ?? '';
    }

    /**
     * removeStylefromContent
     *
     * @return self
     */
    public function removeStylefromContent(): self
    {
        $styleText = $this->getStylefromContent();
        $this->contents = str_replace($styleText, '', $this->contents());
        return $this;
    }

    /**
     * removeDefsfromContent
     *
     * @return self
     */
    public function removeDefsfromContent(): self
    {
        $styleText = $this->getDefsfromContent();
        $this->contents = str_replace($styleText, '', $this->contents());
        return $this;
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

        $name  = $this->id ?? '';
        $style = $this->style;
        if (empty($style)) {
            $style = new Style('', [], $this);
            $this->style = $style;
        }

        $svgAttributes = $this->getOnlySvgAttribute();

        foreach ($param as $svg) {

            if (isset($svg->defs)) {
                $style->mergeStyles($svg->style);
                unset($svg->style);
            }

            if (isset($svg->defs)) {
                if (empty($this->defs)) {
                    $this->defs = [$svg->defs];
                } else {
                    $this->defs[] = $svg->defs;
                }
            }
            unset($svg->defs);
            $svgAttributes = array_merge($svgAttributes, $svg->getOnlySvgAttribute());
            $svg->removeSvgAttribute();
            $newAttributes = $svg->attributes();

            $gNew = new G('', $newAttributes, $this);
            foreach ($svg->elements as $element) {
                $elementName = $element->name;
                $gNew->elements[] = $element;
                if (isset($gNew->$elementName)) {
                    array_unshift($gNew->$elementName, $element);
                } else {
                    $gNew->$elementName = [$element];
                }
            }

            unset($gNew->defs);
            array_unshift($this->elements, $gNew);
            if (isset($this->g)) {
                $this->g[] = $gNew;
            } else {
                $this->g = [$gNew];
            }
        }
        $this->removeAllattributes();
        foreach ($svgAttributes as $key => $svgAttribute) {
            $this->setAttribute($key, $svgAttribute);
        }
        $this->id($name);
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
        return $svg->elements;
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

    /**
     * removeSvgAttribute
     *
     * @return void
     */
    public function removeSvgAttribute()
    {
        $svgAttributes = self::SVG_ATTRIBUTES;
        foreach ($svgAttributes as $att) {
            if (isset($this->attributes[$att])) {
                $this->removeAtt($att);
            }
        }
        foreach ($this->attributes() as $att => $val) {

            if (preg_match('/xml[:]?[^=]*/', $att, $match) !== 0) {
                $this->removeAtt($att);
            }
        }
    }

    /**
     * getOnlySvgAttribute
     *
     * @return array
     */
    public function getOnlySvgAttribute(): array
    {
        $response = [];
        $svgAttributes = self::SVG_ATTRIBUTES;
        foreach ($svgAttributes as $att) {
            if (isset($this->attributes[$att])) {
                $response[$att] = $this->attributes[$att];
            }
        }
        foreach ($this->attributes() as $att => $val) {

            if (preg_match('/xml[:]?[^=]*/', $att, $match) !== 0) {
                $response[$att] = $this->attributes[$att];
            }
        }
        return $response;
    }
}
