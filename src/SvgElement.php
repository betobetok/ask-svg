<?php

declare(strict_types=1);

namespace BladeUI\Icons;

use BladeUI\Icons\Concerns\RendersAttributes;
use BladeUI\Icons\Configurators\Style;
use Exception;
use Illuminate\Contracts\Support\Htmlable;
use NumPHP\Core\NumArray;

/**
 * SvgElement
 */
class SvgElement implements Htmlable
{
    use RendersAttributes;

    public const SVG_ATTRIBUTES = [
        'view-box',
        'version',
        'width',
        'height'
    ];

    public const GRAPH_ELEMENTS = [
        'clipPath',
        'g',
        'line',
        'rect',
        'circle',
        'ellipse',
        'path',
        'text',
        'image',

    ];

    public const NON_GROUP_ELEMENTS = [
        'line',
        'rect',
        'circle',
        'ellipse',
        'path',
        'image',
        'use',
        'polyline',
        'polygon',
    ];

    public const GROUP_ELEMENTS = [
        'g',
        'style',
        'text',
        'clipPath',
        'title',
        'defs',
        'filter',
        'mask',
        'pattern',
        'font'
    ];

    public const SHAPES = [
        'line',
        'rect',
        'circle',
        'ellipse',
        'path',
        'polyline',
        'polygon',
        'text',
    ];

    /** @var string $name */
    protected $name;

    /** @var string $contents */
    protected $contents;

    /** @var SvgElement $context */
    protected $context;

    /** @var Transformation $transforms */
    protected $transforms;

    /** @var bool $isTransformable */
    protected $isTransformable = false;

    /**
     * @var string      $name   the name of a svg element (e. g, svg, path, circle, etc)
     * @var string      $content  the content of a svg file or a part of them
     * @var array       $attributes  the array with the attributres for this element
     * @var SvgElement  $context 
     */
    public function __construct(string $name, string $contents, array $attributes = [], SvgElement $context = null)
    {

        $this->name = $name;
        $this->contents = $contents;
        $this->context = $context;

        $this->getTransformations();
        if (!in_array($name, self::NON_GROUP_ELEMENTS) && $name !== 'style') {
            $this->getAllElements();
        }
        if ($name !== 'style') {
            $this->removeContents();
        }
    }


    protected function configAttributesAndContent(string $tag, string $contents, array $attributes): string
    {
        $svg = preg_match("/<" . $tag . "[^>]*>/i", $contents, $svgTag);

        if ($svg !== 0 && $svg !== false) {
            $attributes = $this->mergeAttributes($svgTag[0], $attributes);
            $contentFirst = strlen($svgTag[0]);
            $contetnleng = strrpos($contents, '</' . $tag . '>') - $contentFirst;
            $contents = substr($contents, $contentFirst, $contetnleng);
        }

        unset($attributes['id']);

        foreach ($attributes as $key => $attribute) {
            $this->$key($attribute);
        }

        return $contents;
    }

    public function __get($name)
    {
        try {
            if (method_exists($this, $name)) {
                return $this->$name();
            }
            if (property_exists($this, $name)) {
                return $this->$name;
            }
            // if (property_exists($this, 'g')) {
            //     foreach ($this->g as $gs) {
            //         $this->elements[$name] = $gs->$name;
            //     }
            // }
            // if (property_exists($this, 'clipPath')) {
            //     foreach ($this->g as $gs) {
            //         $this->elements[$name] = $gs->$name;
            //     }
            // }
        } catch (Exception $e) {
            throw "This Methode don't exist" . $e->getMessage() . "\n";
        }
    }

    /**
     * name
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * id
     *
     * @param string $arg
     * @return string|self
     */
    public function id($arg = '')
    {
        if (empty($arg)) {
            return $this->attributes()['id'] ?? '';
        } else {
            $this->setAttribute('id',  $arg);
            return $this;
        }
    }

    /**
     * transforms get the Transformation Object for the element 
     *
     * @return Transformation
     */
    public function transforms(): Transformation
    {
        return $this->transforms;
    }
    /**
     * contents
     *
     * @return string
     */
    public function contents(): string
    {
        if (isset($this->contents)) {
            return $this->contents;
        }
        $elements = get_object_vars($this);
        if ($elements === false) {
            return '';
        }
        $ret = '';
        foreach ($elements as $element) {
            if (is_array($element)) {
                foreach ($element as $svg) {
                    if ($svg instanceof SvgElement) {
                        $ret .= $svg->toHtml() . "\n";
                    }
                }
            }
        }

        return $ret;
    }

    /**
     * removeContents
     *
     * @return self
     */
    public function removeContents(): self
    {
        unset($this->contents);
        return $this;
    }

    /**
     * setContents
     *
     * @param  string $contents
     * @return self
     */
    public function setContents(string $contents): self
    {
        $this->contents = $contents;
        return $this;
    }

    /**
     * getContext
     *
     * @return self
     */
    public function getContext(): self
    {
        return $this->context;
    }

    /**
     * getAllElements
     *
     * @return void
     */
    public function getAllElements(): void
    {

        foreach (self::GRAPH_ELEMENTS as $type) {
            $element = $this->getElements($type);
            if ($element !== false && isset($element[0]) && $element[0] !== []) {
                $this->$type = $element;
            }
        }
    }


    /**
     * mergeAttributes
     *
     * @param string    $tag
     * @param array     $attributes
     *
     * @return array
     */
    public function mergeAttributes(string $tag, array $attributes): array
    {
        $attributesFromString = $this->getElementAttributes($tag);
        return array_merge($attributes, $attributesFromString);
    }

    /**
     * getElements
     *
     * @param  string    $element
     * 
     * @return SvgElement|false
     */
    public function getElements(string $element)
    {
        $ret = [];
        if (in_array($element, self::GROUP_ELEMENTS) || $element === 'svg') {
            if ($this->contents() !== '') {
                $ret = $this->findGroupElement($this->contents(), $element);
            }
        } elseif (in_array($element, self::NON_GROUP_ELEMENTS)) {
            $content = $this->removeGroupElements($this->contents());
            $ret = $this->findNonGroupElement($content, $element);
        } else {
            return false;
        }
        return $ret;
    }

    /**
     * removeGroupElements
     *
     * @param  string $contents
     * @return string
     */
    public function removeGroupElements(string $contents): string
    {
        $ret = $contents;
        foreach (self::GROUP_ELEMENTS as $groupElement) {
            $count = mb_substr_count($ret, '<' . $groupElement);
            if ($count <= 0) {
                continue;
            }

            if ($count === 1) {
                $posStart = stripos($ret, '<' . $groupElement);
                $posEnde = stripos($ret, '</' . $groupElement . '>');
                $cont = trim(substr($ret, $posStart, $posEnde - $posStart + strlen('</' . $groupElement . '>')));
                $ret = str_replace($cont, '', $ret);
                continue;
            }

            $posStart[0] = stripos($ret, '<' . $groupElement);
            $posEnde[0] = stripos($ret, '</' . $groupElement . '>');
            for ($i = 1; $i < $count; $i++) {
                $posStart[$i] = stripos($ret, '<' . $groupElement, $posStart[$i - 1] + 2);
                $posEnde[$i] = stripos($ret, '</' . $groupElement, $posEnde[$i - 1] + 2);
            }
            for ($i = 0; $i < $count; $i++) {
                $pos[$posStart[$i]] = 1;
                $pos[$posEnde[$i]] = -1;
            }
            ksort($pos);
            $n = 0;

            $first = array_key_first($pos);
            foreach ($pos as $key => $val) {
                if ($first === true) {
                    $first = $key;
                }
                $n += $val;
                if ($n === 0) {
                    preg_match("/<" . $groupElement . "[^>]*>/i", $ret, $tag, 0, $first);
                    $cont = trim(substr($ret, $first, $key - $first + strlen('</' . $groupElement . '>')));
                    $ret = str_replace($cont, '', $ret);
                    $first = true;
                }
            }
        }
        return $ret;
    }

    /**
     * removeComents Deprecate
     *
     * @return self
     */
    public function removeComents(): self
    {
        // $this->contents = preg_replace("/(<!--.+-->\n)/i", '', $this->contents());
        return $this;
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
     * getStylefromContent
     *
     * @return string
     */
    public function getStylefromContent(): string
    {
        preg_match("/(<style[^>]*>)[^á]*<\/style>/i", $this->contents(), $match);
        return $match[0] ?? '';
    }

    public function toHtml(): string
    {
        if (in_array($this->name(), self::GROUP_ELEMENTS)) {
            return sprintf('<' . $this->name() . '%s', $this->renderAttributes()) . '>' . $this->contents() . '</' . $this->name() . '>';
        } elseif (in_array($this->name(), self::NON_GROUP_ELEMENTS)) {
            return sprintf('<' . $this->name() . '%s', $this->renderAttributes()) . '/>';
        } else {
            return '<svg' . sprintf('%s', $this->renderAttributes()) . ' >' . "\n" . $this->contents() . "\n" . '</svg>';
            // return str_replace('<svg', sprintf('<svg%s', $this->renderAttributes()), $this->contents());
        }
    }

    /**
     * removeId
     *
     * @return self
     */
    public function removeId(): self
    {
        if (property_exists($this, 'id')) {
            unset($this->id);
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
        $elem = get_object_vars($this);
        $ret = [];
        foreach ($elem as $key => $element) {
            if ($key === 'contents') {
                continue;
            }
            if (is_a($element, 'BladeUI\Icons\SvgElement')) {
                $ret[$key] =  $element->toArray();
                continue;
            }
            if ($key === 'attributes') {
                $ret['attributes'] =  $this->attributes();
                foreach ($ret['attributes'] as $k => $att) {
                    $ret['attributes'][$k] = str_replace(['\"', '"'], '', $att);
                }
                continue;
            }
            if (is_array($element)) {
                foreach ($element as $k => $elm) {
                    if (is_a($elm, 'BladeUI\Icons\SvgElement')) {
                        $ret[$key . '-' . $k] = $elm->toArray();
                    } else {
                        $ret[$key . '-' . $k] = $elm;
                    }
                }
                continue;
            }
            $ret[$key] =  $element;
        }
        return $ret;
    }

    public function serialize($data)
    {
        return json_encode($this->toArray());
    }

    public function unserialize($data)
    {
    }

    /**
     * findGroupElement
     *
     * @param  string $content
     * @param  string $element
     * @return array
     */
    public function findGroupElement(string $content, string $element): array
    {
        $ret = [];
        $count = mb_substr_count($content, '<' . $element);
        if ($count <= 0) {
            return [];
        }

        if ($count === 1) {
            $posStart = stripos($content, '<' . $element);
            $posEnde = stripos($content, '</' . $element . '>');
            $tag = trim(substr($content, $posStart,  stripos($content, '>', $posStart) - $posStart + 1));
            $cont = trim(substr($content, $posStart + strlen($tag), $posEnde - $posStart - strlen($tag)));
            $attributes = $this->getElementAttributes($tag);
            $classElement = 'BladeUI\\Icons\\Shapes\\' . ucfirst($element);
            $classElement2 = 'BladeUI\\Icons\\Configurators\\' . ucfirst($element);
            if (class_exists($classElement)) {
                $tmp = new $classElement($cont, $attributes, $this);
            } elseif (class_exists($classElement2)) {
                $tmp = new $classElement2($cont, $attributes, $this);
            } else {
                $tmp = new SvgElement($element, $cont, $attributes, $this);
            }
            $ret[] = $tmp;
            return $ret;
        }

        $posStart[0] = stripos($content, '<' . $element);
        $posEnde[0] = stripos($content, '</' . $element . '>');
        for ($i = 1; $i < $count; $i++) {
            $posStart[$i] = stripos($content, '<' . $element, $posStart[$i - 1] + 2);
            $posEnde[$i] = stripos($content, '</' . $element, $posEnde[$i - 1] + 2);
        }
        for ($i = 0; $i < $count; $i++) {
            $pos[$posStart[$i]] = 1;
            $pos[$posEnde[$i]] = -1;
        }
        ksort($pos);
        $n = 0;

        $first = array_key_first($pos);
        foreach ($pos as $key => $val) {
            if ($first === true) {
                $first = $key;
            }
            $n += $val;
            if ($n === 0) {
                preg_match("/<" . $element . "[^>]*>/i", $content, $tag, 0, $first);
                $con = trim(substr($content, $first + strlen($tag[0]) + 1, $key - $first - strlen($tag[0]) - 1));
                $attributes = $this->getElementAttributes($tag[0]);
                $classElement = 'BladeUI\\Icons\\Shapes\\' . ucfirst($element);
                $classElement2 = 'BladeUI\\Icons\\Configurators\\' . ucfirst($element);
                if (class_exists($classElement)) {
                    $tmp = new $classElement($con, $attributes, $this);
                } elseif (class_exists($classElement2)) {
                    $tmp = new $classElement2($con, $attributes, $this);
                } else {
                    $tmp = new SvgElement($element, $con, $attributes, $this);
                }
                $ret[] = $tmp;
                $first = true;
            }
        }
        return $ret;
    }

    /**
     * findNonGroupElement
     *
     * @param  string $content
     * @param  string $element
     * @return array
     */
    public function findNonGroupElement(string $content, string $element): array
    {
        preg_match_all("/<" . $element . "[^>]+\/?>/i", $content, $match);
        $tags = $match === [] ? '' : $match[0];
        $content = '';
        $ret = [];
        if ($tags === false || $tags === []) {
            return [];
        }
        foreach ($tags as $tag) {
            $content = $tag;
            $attributes = $this->getElementAttributes($tag);
            $classElement = 'BladeUI\\Icons\\Shapes\\' . ucfirst($element);
            if (class_exists($classElement)) {
                $tmp = new $classElement($content, $attributes, $this);
            } else {
                $tmp = new SvgElement($element, $content, $attributes, $this);
            }
            $ret[] = $tmp;
        }
        return $ret;
    }

    /**
     * getElementAttributes
     *
     * @param  string $tag
     * @return array
     */
    public function getElementAttributes(string $tag): array
    {
        preg_match_all("/[a-z0-9_$:-]*=[\"'][a-zA-Z0-9().,:;_?=%$+*#\/\n\t\r\s\\-]*[\"']/i", $tag, $attrs);
        $attributes = [];
        foreach ($attrs[0] as $attribute) {
            $attributes[explode('=', $attribute)[0]] = str_replace('"', '', explode('=', $attribute)[1]);
        }
        return  $attributes;
    }


    /**
     * cleanGroup
     *
     * @return self
     */
    public function cleanGroup(): self
    {
        foreach ($this as $component => $svgElement) {
            if ($component === 'g') {
                foreach ($this->$component as $k => $g) {
                    if (property_exists($g, 'g') && count($g->g) === 1) {
                        $gOld = $g->g[0];
                        $gAttribute = $g->attributes();
                        $this->$component[$k] = $gOld;
                        $gAttribute = array_merge($this->$component[$k]->attributes(), $gAttribute);
                        foreach ($gAttribute as $attName => $val) {
                            $this->$component[$k]->$attName($val);
                        }
                    }
                    $g->clean();
                }
            }
        }
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
                unset($this->attributes[$att]);
            }
        }
        foreach ($this->attributes() as $att => $val) {

            if (preg_match('/xml[:]?[^=]*/', $att, $match) !== 0) {
                unset($this->attributes[$att]);
            }
        }
    }

    /**
     * getTransformations
     *
     * @return void
     */
    public function getTransformations()
    {
        $attributes = $this->attributes();
        if (isset($attributes['transform'])) {
            $this->transforms = new Transformation($attributes['transform']);
        } elseif ($this->isTransformable) {
            $this->transforms = new Transformation();
        }

        return $this;
    }

    /**
     * getElementById
     *
     * @param  string $id
     * @return SvgElement|false
     */
    public function getElementById(string $id): ?SvgElement
    {
        $att = $this->attributes();
        if (isset($att['id']) && $att['id'] === $id) {
            return $this;
        }

        foreach ($this::GRAPH_ELEMENTS as $group) {
            if ($group === 'style') {
                continue;
            }
            if (isset($this->$group)) {
                foreach ($this->$group as $element) {
                    $e = $element instanceof SvgElement ? $element->getElementById($id) : '';
                    if ($e !== false && !empty($e)) {
                        return $e;
                    }
                }
            }
        }
        return false;
    }

    /**
     * getStartPointById
     *
     * @param  string $id
     * @return NumArray|null
     */
    public function getStartPointById(string $id): ?NumArray
    {
        $element = $this->getElementById($id);

        if ($element === false) {
            return null;
        }

        $point = $element->getStartPosition();
        $transforms = [];
        $transforms[] = $element->transforms();

        do {
            $element = $element->getContext();
            $transforms[] = $element->transforms();
        } while ($element->hasContext());

        $n = count($transforms) - 1;
        for ($i = 0; $i <= $n; $i++) {
            if (!($transforms[$i] instanceof Transformation)) {
                continue;
            }

            $point = $transforms[$i]->getTransformed($point);
        }
        return $point;
    }

    /**
     * hasContext
     *
     * @return void
     */
    public function hasContext()
    {
        return $this->context !== null;
    }

    /**
     * transform
     *
     * @param  string $transforms
     * @return void
     */
    public function transform(string $transforms)
    {
        $this->setAttribute('transform', $transforms);
        $this->getTransformations();
        return $this;
    }
}
