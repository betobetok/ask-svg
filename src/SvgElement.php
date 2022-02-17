<?php

declare(strict_types=1);

namespace BladeUI\Icons;

use BladeUI\Icons\Concerns\RendersAttributes;
use Exception;
use Illuminate\Contracts\Support\Htmlable;
use BladeUI\Icons\Shapes\Path;
use BladeUI\Icons\Shapes\Shape;
use NumPHP\Core\NumArray;

class SvgElement implements Htmlable
{
    use RendersAttributes;

    use Shape;

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
    ];

    public const GROUP_ELEMENTS = [
        'g',
        'style',
        'text',
        'clipPath',
        'title',
    ];

    public const SHAPES = [
        'line',
        'rect',
        'circle',
        'ellipse',
        'path',
        'polyline',
        'polygon'
    ];

    private string $name;

    private string $contents;

    private $context;

    protected Transformation $transforms;

    public function __construct(string $name, string $contents, array $attributes = [], $context = null)
    {
        $this->name = $name;
        $this->contents = $contents;
        $this->context = $context;
        $svg = preg_match("/<svg[^>]*>/i", $contents, $svgTag);
        if ($svg !== 0 && $svg !== false && $attributes === []) {
            $attributes = $this->getElementAttributes($svgTag[0]);
        }
        foreach ($attributes as $key => $attribute) {
            $this->$key($attribute);
        }
        $this->getTransformations();
        if (!in_array($name, self::NON_GROUP_ELEMENTS)) {
            $this->getAllElements();
        }
        if ($name !== 'style') {
            $this->removeContents();
        }
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
            if (property_exists($this, 'g')) {
                foreach ($this->g as $gs) {
                    $this->elements[$name] = $gs->$name;
                }
            }
            if (property_exists($this, 'clipPath')) {
                foreach ($this->g as $gs) {
                    $this->elements[$name] = $gs->$name;
                }
            }
        } catch (Exception $e) {
            throw "This Methode don't exist" . $e->getMessage() . "\n";
        }
    }

    public function name(): string
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $this->name = $name;
        return $this;
    }

    public function transforms()
    {
        return $this->transforms;
    }
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

    public function removeContents(): self
    {
        unset($this->contents);
        return $this;
    }

    public function setContents($contents): self
    {
        $this->contents = $contents;
        return $this;
    }

    public function getContext(): self
    {
        return $this->context;
    }

    public function getAllElements(): void
    {

        foreach (self::GRAPH_ELEMENTS as $type) {
            $element = $this->getElements($type);
            if ($element !== false && isset($element[0]) && $element[0] !== []) {
                $this->$type = $element;
            }
        }
    }

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

    public function removeComents(): self
    {
        $this->contents = preg_replace("/(<!--.+-->\n)/i", '', $this->contents());
        return $this;
    }

    public function removeStylefromContent(): self
    {
        $this->contents = preg_replace("/(<[^>]*>[^<]*<\/style>\n)/i", '', $this->contents());
        return $this;
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

    public function removeId(): self
    {
        if (property_exists($this, 'id')) {
            unset($this->id);
        }
        return $this;
    }

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
            $tmp = new SvgElement($element, $cont, $attributes, $this);
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
                $tmp = new SvgElement($element, $con, $attributes, $this);
                $ret[] = $tmp;
                $first = true;
            }
        }
        return $ret;
    }

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
                $tmp = new $classElement($element, $content, $attributes, $this);
            } else {
                $tmp = new SvgElement($element, $content, $attributes, $this);
            }
            $ret[] = $tmp;
        }
        return $ret;
    }

    public function getElementAttributes(string $tag): array
    {
        preg_match_all("/[a-z0-9_$:-]*=[\"'][a-zA-Z0-9().,:;_?=%$+*#\/\n\t\r\s\\-]*[\"']/i", $tag, $attrs);
        $attributes = [];
        foreach ($attrs[0] as $attribute) {
            $attributes[explode('=', $attribute)[0]] = str_replace('"', '', explode('=', $attribute)[1]);
        }
        return  $attributes;
    }

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

    public function getTransformations()
    {
        $attributes = $this->attributes();
        if (isset($attributes['transform'])) {
            $this->transforms = new Transformation($attributes['transform']);
        }

        return $this;
    }

    public function getElementById(string $id)
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

    public function getStartPointById(string $id): NumArray
    {
        $element = $this->getElementById($id);
        $point = $element->getStartPosition();
        $transforms = [];
        $transforms[] = $element->transforms();
        do {
            $element = $element->getContext();
            $transforms[] = $element->transforms();
        } while ($element->hasContext());
        // dump([
        //     'transformaciones' => $transforms,
        //     'id'=>$id
        // ]);
        $n = count($transforms)-1;
        for ($i = 0; $i <= $n; $i++) {
            if (!($transforms[$i] instanceof Transformation)) {
                continue;
            }
            // dump([$point,$transforms[$i]]);
            $point = $transforms[$i]->getTransformed($point);
        }
        return $point;
    }

    public function hasContext()
    {
        return $this->context !== null;
    }

    public function transform(string $transforms)
    {
        $this->setAttribute('transform', $transforms);
        $this->getTransformations();
        return $this;
    }
}
