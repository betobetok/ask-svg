<?php

declare(strict_types=1);

namespace ASK\Svg;

use ASK\Svg\Configurators\G;
use ASK\Svg\Configurators\Style;
use ASK\Svg\Exceptions\SvgNotFound;

/**
 * the Svg document
 */
final class Svg extends SvgElement implements Conteiner
{
    /** @var Style $style */
    public Style $style;

    public function __construct(string $fileName, string $contents, array $attributes = [])
    {
        $name = explode('/', $fileName);
        $this->id(implode('-', $name));
        if (is_array($name) && isset($name[1])) {
            $this->name = $name[1];
        } elseif (is_array($name) && count($name) === 1) {
            $this->name = $name[0];
        }

        $this->contents = $contents;

        $styleContent = $this->getStylefromContent();
        $this->style = new Style($styleContent, [], $this);
        $this->removeStylefromContent();

        $this->contents = $this->configAttributesAndContent('svg', $this->contents, $attributes);
        $this->formatSvgAtributes();
        $this->contents = $this->replaceClasses($this->style, $this->contents);

        parent::__construct($this->name, $this->contents);

        $this->cleanContent();
    }

    /**
     * get the Style element from the string content
     *
     * @return string
     */
    public function getStylefromContent(): string
    {
        preg_match("/(<style[^>]*>)[^á]*<\/style>/i", $this->contents(), $match);
        return $match[0] ?? '';
    }

    /**
     * remove the string Style from the string content
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
     * get the Style element or set rules in the style element
     * 
     * * if it's called without arguments, return the Style Object
     * * if it's called with arguments, set the rules gived in the arguments array
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
     * @return Style|null
     */
    public function style(array $arg = []): ?Style
    {
        if (count($arg) <= 0) {
            return $this->style;
        }

        $this->style->rules($arg);
    }

    /**
     * set the Style element
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
     * replace the Classes names in the string content
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
     * merge one or more Svgs in this svg
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

    /**
     * implements of Htmlable, toHtml return a string form of the svg in HTML format
     *
     * @return string
     */
    public function toHtml(): string
    {
        return '<svg' . sprintf('%s', $this->renderAttributes()) . ' >' . $this->contents() . '</svg>';
    }

    /**
     * get all the Svg elements in this svg, 
     * this array conteins all the elements in order
     *
     * @param  mixed $svg
     * @return array
     */
    public function getAllSvgElements(Svg $svg): array
    {
        return $svg->elements;
    }

    /**
     * cleanContent remove all string contents in the complet object
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
     * (Conteiner implement) //TODO implementation of container
     */
    public function getContent()
    {
        return $this;
    }

    /**
     * Set the value of content
     * (Conteiner implement) //TODO implementation of container
     *
     * @return  self
     */
    public function setContent($content)
    {
        return $this;
    }

    /**
     * removes those attributes that belong exclusively to the svg element
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
     * get those attributes that belong exclusively to the svg element
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

    public function formatSvgAtributes()
    {
        $attributes = $this->attributes();
        $this->removeAllAttributes();
        foreach ($attributes as $att => $val) {
            if ($att !== 'style') {
                $this->$att($val);
            } else {
                $this->setAttribute('style', $val);
            }
        }
    }
    /**
     * save
     *
     * @param  mixed $fileName
     * @return void
     */
    public function save($fileName = '')
    {
        $setFile = explode('-', $this->id());
        if (count($setFile) <= 1) {
            $file = $setFile;
            $set = 'default';
        } else {
            $file = $setFile[1];
            $set = $setFile[0];
        }
        if (!empty($fileName)) {
            $file = $fileName;
        }
        if (empty($set)) {
            $set = 'default';
        }
        $setPath = app(Factory::class)->getSetByPrefix($set)['paths'][0];
        $filePath = $setPath . '/' . $file;
        if (!file_put_contents($filePath, $this->toHtml())) {
            throw SvgNotFound::pathNotExist($filePath);
        }
    }
}
