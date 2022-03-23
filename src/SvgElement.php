<?php

declare(strict_types=1);

namespace ASK\Svg;

use ASK\Svg\Concerns\RendersAttributes;
use ASK\Svg\Shapes\Shape;
use Exception;
use Illuminate\Contracts\Support\Htmlable;
use NumPHP\Core\NumArray;

/**
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

    /**
     * Make this element Transformable
     *
     * @return void
     */
    public function makeTransformable()
    {
        $this->isTransformable = true;
        $this->getTransformations();
    }

    /**
     * make this element Untransformable
     *
     * @return void
     */
    public function makeUntransformable()
    {
        $this->isTransformable = false;
        unset($this->transforms);
    }

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
     * get or set the name of the element
     *
     * @param string $arg

     * @return string|self
     */
    public function name(string $arg = ''): string
    {
        if (empty($arg)) {
            return $this->name ?? '';
        } else {
            $this->name = $arg;
            return $this;
        }
    }

    /**
     * get or set the id attribute of the element in the attributes property
     *
     * @param string $arg
     * 
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
     * set or get the Transformation Object for the element 
     *
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
    }
    /**
     * get the string contents of the elements
     * - this method is similar at toHtml 
     *   but get just the string of the content without the tag string
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
     * remove the string Contents property from the object
     *
     * @return self
     */
    public function removeContents(): self
    {
        unset($this->contents);
        return $this;
    }

    /**
     * set the string Contents property in the object
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
     * get the property Context that is the parent SVG element of this element
     *
     * @return SvgElement|null
     */
    public function getContext(): ?SvgElement
    {
        return $this->context;
    }

    /**
     * get all Elements from the string content
     * this method make all found elements as SvgElement Objects and
     * put in a property with the element name
     *
     * @return void
     */
    public function getAllElements(): void  //TODO get comentar as element
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

    /**
     * set a neu Element in this object
     *
     * @param  string $name
     * @param  mixed $element
     * @return void
     */
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
     * merge the attributes conteined in a string tag with the array attributes
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
     * remove coments from the string content
     *
     * @return self
     */
    public function removeComents(): self
    {
        if (!isset($this->contents)) {
            return $this;
        }
        $this->contents = preg_replace("/(<!--.+-->\n?)/i", '', $this->contents);
        return $this;
    }

    /**
     * implements of Htmlable, toHtml return a string form of the svg element in HTML format
     *
     * @return string
     */
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
     * remove the property Id
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
     * find the first group element with the name $element and 
     * return the new corresponding SvgElement instance
     * or null if not found
     *
     * @param  string $element
     * @return SvgElement|null
     */
    public function findFirstGroupElement(string $element): ?SvgElement
    {
        $content = $this->contents;
        if (empty($content)) {
            $this->findFirstElement($element);
        }
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

    /**
     * return the first Element in the elements array
     * or null if not found
     *
     * @param  string $elementName
     * @return SvgElement|null
     */
    public function findFirstElement(string $elementName): ?SvgElement
    {
        foreach ($this->elements as $element) {
            if ($element->name() === $elementName) {
                return $element;
            }
        }
        return null;
    }

    /**
     * find the first non-group element with the name $element and 
     * return the new corresponding SvgElement instance
     * or null if not found
     *
     * @param  string $element
     * @return SvgElement|null
     */
    public function findFirstNonGroupElement(string $element): ?SvgElement
    {
        $content = $this->contents;
        if (empty($content)) {
            $this->findFirstElement($element);
        }
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
     * get the attributes of a element froma string tag
     * (<tag attribute="value"></tag>)
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
     * get the Transformations of the element and make the corresponding Transformation Object
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
     * find an element by its id and return it
     * or null if not found
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
     * get the start position point of an Shape element by its Id
     * and return it or null if not found
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
        $transforms[] = $element->transform();

        do {
            $element = $element->getContext();
            $transforms[] = $element->transform();
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
     * return true if the element has content or false otherwise
     *
     * @return void
     */
    public function hasContext()
    {
        return $this->context !== null;
    }
}
