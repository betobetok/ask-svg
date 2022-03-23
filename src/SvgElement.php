<?php

declare(strict_types=1);

namespace BladeUI\Icons;

<<<<<<< Updated upstream
use BladeUI\Icons\Concerns\RendersAttributes;
=======
use ASK\Svg\Concerns\RendersAttributes;
use ASK\Svg\Shapes\Shape;
>>>>>>> Stashed changes
use Error;
use Exception;
use Illuminate\Contracts\Support\Htmlable;
use NumPHP\Core\NumArray;

/**
<<<<<<< Updated upstream
 * SvgElement
=======
 * # An element belonging to a svg structure
 * 
 * This object represents all the elements within an svg document
 *
 * from the svg parent element to the internal elements or 
 * figures such as <path>, <circle> or <g>, passing through 
 * configuration elements such as <style>, <defs> among others 
 *
 * all elements are accessible in order through the elements array, 
 * as well as through the array in the element name property
 *
 * for example 
 * @example $svg->g returns all <g> elements
 * @example $svg->elements[0] return the first element in the <svg></svg>
 * @example $svg->g[0]->elements[0] return the first element in the <g></g>
 * 
 * @author  Alberto Solorzano Kraemer
 *
 * @since 1.0
>>>>>>> Stashed changes
 */
class SvgElement implements Htmlable
{
    use RendersAttributes;

    /** @ignore */
    public const SVG_ATTRIBUTES = [
        'version',
        'width',
        'height',
        'viewBox',
    ];

    /** @ignore */
    public const GRAPH_ELEMENTS = [
        'clipPath',
        'g',
        'line',
        'rect',
        'circle',
        'ellipse',
        'path',
        'image',
        'use',
        'polyline',
        'polygon',
        'text',
        'pattern',
        'defs',
        'linearGradient',
        'radialGradient',
    ];

    /** @ignore */
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
        'defs',
        'linearGradient',
        'radialGradient',
        'stop',
    ];

    /** @ignore */
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
        'font',
        'linearGradient',
        'radialGradient',
    ];

    /** @ignore */
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

    /** @ignore */
    private const TO_REPLACE = [
        'serch' => [
            'lineargradient',
            'radialgradient',
            'clippath',
            'viewbox',
        ],
        'replace' => [
            'linearGradient',
            'radialGradient',
            'clipPath',
            'viewBox',
        ],
    ];

    /** @var string $name */
    protected $name;

    /** @var SvgElement[] $elements = []*/
    protected $elements = [];

    /** @var string $contents */
    protected $contents;

    /** @var SvgElement $context */
    protected $context;

    /** @var Transformation $transforms */
    protected $transforms;

    /** @var bool $isTransformable = false*/
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
        $this->transforms = [];
        $this->contents = trim(preg_replace("/(\s){2,}/i", ' ', $contents));
        $this->contents = trim(str_replace(self::TO_REPLACE['serch'], self::TO_REPLACE['replace'], $contents));
        $this->context = $context;

        $this->configAttributesAndContent('', '', $attributes);

        $this->getTransformations();

        if ((in_array($name, self::GROUP_ELEMENTS) && $name !== 'style') || !in_array($name, self::NON_GROUP_ELEMENTS)) {
            $this->getAllElements();
        }
        if ($name !== 'style') {
            $this->removeContents();
        }
    }

    public function makeTransformable()
    {
        $this->isTransformable = true;
        $this->getTransformations();
    }

    public function makeUntransformable()
    {
        $this->isTransformable = false;
        unset($this->transforms);
    }

<<<<<<< Updated upstream
=======
    /**
     * @internal configAttributesAndContent
     * prepare the content and attributes when a new Svg element is creted
     *
     * @param  string $tag
     * @param  string $contents
     * @param  array $attributes
     * 
     * @return string
     */
>>>>>>> Stashed changes
    protected function configAttributesAndContent(string $tag, string $contents, array $attributes): string
    {
        $svg = preg_match("/<" . $tag . "[^>]*>/i", $contents, $svgTag);

        if ($svg !== 0 && $svg !== false) {
            $attributes = $this->mergeAttributes($svgTag[0], $attributes);
            if ($tag === 'svg') {
                $contentFirst = strrpos($contents,  $svgTag[0]) + strlen($svgTag[0]);
                $contetnleng = strrpos($contents, '</' . $tag . '>') - $contentFirst;
                $contents = substr($contents, $contentFirst, $contetnleng);
                unset($attributes['id']);
            }
        }

        foreach ($attributes as $key => $attribute) {
            $this->$key($attribute);
        }

        return $contents;
    }

    /**
     * @ignore __get
     *
     * @param  mixed $name
     * @return void
     */
    public function __get($name)
    {
        try {
            if (method_exists($this, $name)) {
                return $this->$name();
            }
            if (property_exists($this, $name)) {
                return $this->$name;
            }
        } catch (Exception $e) {
            throw $e->getMessage();
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
<<<<<<< Updated upstream
     * @return Transformation
     */
    public function transforms(): Transformation
    {
        return $this->transforms;
=======
     * @return Transformation|string
     * 
     * @return string|self|null
     */
    public function transform($arg = null)
    {
        if (empty($arg) && isset($this->transforms)) {
            return $this->transforms;
        } elseif (empty($arg) && !isset($this->transforms)) {
            return null;
        } else {
            if (is_string($arg)) {
                $this->setAttribute('transform', $arg);
                $this->getTransformations();
                return $this;
            } elseif ($arg instanceof Transformation) {
                $this->transforms = $arg;
                return $this;
            }
        }
>>>>>>> Stashed changes
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

        $ret = '';
        foreach ($this->elements as $element) {
            if ($element instanceof SvgElement) {
                $ret .= $element->toHtml() . "\n";
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
        if (!empty($this->elements)) {
            return;
        }
        $groupElements = [];
        $nonGroupElements = [];
        foreach (self::GROUP_ELEMENTS as $element) {
            preg_match("/<(" . $element . ")(\s|>)([^>])*>/i", $this->contents, $tag);
            if (!empty($tag) && $element !== 'svg') {
                $groupElements[] = $element;
            }
        }

        foreach (self::NON_GROUP_ELEMENTS as $element) {
            $pos = preg_match("/<(" . $element . ")(\s|>)([^>])*\/?>/i", $this->contents, $tag);
            if (!empty($tag)) {
                $nonGroupElements[] = $element;
            }
        }

        while (strlen($this->contents) > 0) {
            preg_match("/<([^\/][^>\s]*)([^>\/]*)(\/?)>/i", $this->contents, $tag);
            if (empty($tag) || $tag[1] === 'style') {
                break;
            }
            $name = $tag[1];

            $pos = strpos($this->contents, $tag[0]);
            $posClose = strpos($this->contents, '</' . $name);

            if (!empty($tag[3])) {
                $ret = $this->findFirstNonGroupElement($name);
                $this->setElement($name, $ret);
            } elseif (in_array($name, $groupElements)) {
                $ret = $this->findFirstGroupElement($name);
                $this->setElement($name, $ret);
            } elseif (in_array($name, $nonGroupElements)) {
                $ret = $this->findFirstNonGroupElement($name);
                $this->setElement($name, $ret);
            } elseif (!empty($tag[0]) && $pos !== false) {
                if ($posClose !== false) {
                    $ret = $this->findFirstGroupElement($name);
                    $this->setElement($name, $ret);
                } else {
                    $ret = $this->findFirstNonGroupElement($name);
                    $this->setElement($name, $ret);
                }
            }
        }
    }

    public function setElement(string $name, ?SvgElement $element)
    {
        if (!empty($element) && $name !== 'style') {
            $this->elements[] = $element;
            if (isset($this->$name)) {
                $this->$name[] = $element;
            } else {
                $this->$name = [$element];
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
            if ($this->contents !== '') {
                $ret = $this->findGroupElement($this->contents, $element);
            }
        } elseif (in_array($element, self::NON_GROUP_ELEMENTS)) {
            $content = $this->removeGroupElements($this->contents);
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
            $tmp = [];
            foreach ($pos as $key => $val) {
                if ($first === true) {
                    $first = $key;
                }
                $n += $val;
                if ($n === 0) {
                    preg_match("/<" . $groupElement . "[^>]*>/i", $ret, $tag, 0, $first);
                    $tmp[] = trim(substr($ret, $first, $key - $first + strlen('</' . $groupElement . '>')));
                    $first = true;
                }
            }
            $ret = trim(str_replace($tmp, '', $ret));
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


    public function toHtml(): string
    {
        if (in_array($this->name(), self::GROUP_ELEMENTS)) {
            return sprintf('<' . $this->name() . '%s', $this->renderAttributes()) . '>' . $this->contents() . '</' . $this->name() . '>';
        } elseif (in_array($this->name(), self::NON_GROUP_ELEMENTS)) {
            return sprintf('<' . $this->name() . '%s', $this->renderAttributes()) . '/>';
        } else {
            return sprintf('<' . $this->name() . '%s', $this->renderAttributes()) . '>' . $this->contents() . '</' . $this->name() . '>';
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
            if (is_a($element, __NAMESPACE__ . '\SvgElement')) {
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
                    if (is_a($elm, __NAMESPACE__ . '\SvgElement')) {
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
     * @return SvgElement|null
     */
    public function findFirstGroupElement(string $element): ?SvgElement
    {

        $content = $this->contents;
        $count = preg_match_all("/<(" . $element . ")(?:\s)?([^>\/]*)(\/)?>/i", $content, $tag);
        if ($count <= 0) {
            return null;
        }

        $i = 0;
        $group = true;
        while ($group && $i < $count) {
            if (empty($tag[3][$i])) {
                $posStart[0] = stripos($content, $tag[0][$i]);
                $group = false;
            }
            $i++;
        }
        if (!isset($posStart)) {
            return null;
        }

        $posEnde[0] = stripos($content, '</' . $element . '>');
        for ($i = 1; $i < $count; $i++) {
            if (empty($tag[3][$i])) {
                $posStart[$i] = stripos($content, $tag[0][$i], $posStart[$i - 1] + 1);
                $posEnde[$i] = stripos($content, '</' . $element, $posEnde[$i - 1] + 1);
            }
        }
        $count = count($posEnde);
        if ($count <= 0) {
            return null;
        }
        for ($i = 0; $i < $count; $i++) {
            $pos[$posStart[$i]] = 1;
            $pos[$posEnde[$i]] = -1;
        }

        ksort($pos);
        $n = 0;

        $first = array_key_first($pos);
        $elementsToRemove = [];

        foreach ($pos as $key => $val) {
            if ($first === true) {
                $first = $key;
            }
            $n += $val;
            if ($n === 0) {
                preg_match("/<" . $element . "[^>]*>/i", $content, $tag2, 0, $first);
                $con = trim(substr($content, $first + strlen($tag2[0]) + 1, $key - $first - strlen($tag2[0]) - 1));
                $attributes = $this->getElementAttributes($tag2[0]);
                $classElement = __NAMESPACE__ . '\\Shapes\\' . ucfirst($element);
                $classElement2 = __NAMESPACE__ . '\\Configurators\\' . ucfirst($element);
                if (class_exists($classElement)) {
                    $tmp = new $classElement($con, $attributes, $this);
                } elseif (class_exists($classElement2)) {
                    $tmp = new $classElement2($con, $attributes, $this);
                } else {
                    $tmp = new SvgElement($element, $con, $attributes, $this);
                }

                $elementsToRemove[] = trim(substr($content, $first, $key - $first + strlen('</' . $element . '>')));
                $first = true;
                $this->contents = trim(str_replace($elementsToRemove, '', $content));
                return $tmp;
            }
        }
    }

    public function fillterPositions($element)
    {
        return $element !== false;
    }

    /**
     * findNonGroupElement
     *
     * @param  string $element
     * @return SvgElement|null
     */
    public function findFirstNonGroupElement(string $element): ?SvgElement
    {
        $content = $this->contents;
        preg_match("/<" . $element . "[^>]+\/?>/i", $content, $match);

        $tag = $match === [] ? '' : $match[0];
        $content = '';

        if ($tag === false || $tag === [] || $tag === '') {
            return null;
        }

        $content = '';
        $attributes = $this->getElementAttributes($tag);
        $classElement = __NAMESPACE__ . '\\Shapes\\' . ucfirst($element);
        $classElement2 = __NAMESPACE__ . '\\Configurators\\' . ucfirst($element);
        if (class_exists($classElement)) {
            $tmp = new $classElement($content, $attributes, $this);
        } elseif (class_exists($classElement2)) {
            $tmp = new $classElement2($content, $attributes, $this);
        } else {
            $tmp = new SvgElement($element, $content, $attributes, $this);
        }
        $this->contents = str_replace($tag, '', $this->contents);
        return $tmp;
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
     * getTransformations
     *
     * @return void
     */
    public function getTransformations()
    {
        $attributes = $this->attributes();
        if (isset($attributes['transform'])) {
            $this->transforms = new Transformation($attributes['transform']);
            $this->isTransformable = true;
        } elseif ($this->isTransformable) {
            $this->transforms = new Transformation();
        } elseif (!$this->isTransformable) {
            unset($this->transforms);
        }

        return $this;
    }

    /**
     * getElementById
     *
     * @param  string $id
     * @return SvgElement|null
     */
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

        if (!($element instanceof Shape)) {
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
