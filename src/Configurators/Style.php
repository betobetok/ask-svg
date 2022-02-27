<?php

declare(strict_types=1);

namespace BladeUI\Icons\Configurators;

use BladeUI\Icons\Concerns\RendersAttributes;
use BladeUI\Icons\Configurators\Configurator;
use BladeUI\Icons\SvgElement;

/**
 * Style
 */
class Style extends Configurator
<<<<<<< Updated upstream
{    
    /** classes @var array */
    private $classes;

    public function __construct(string $svgContent, string $name)
    {
        parent::__construct('style', $svgContent);
        $this->renameStyle($name);
        $this->removeContents();
    }
    
=======
{
    /** @var array $classes */
    private $classes;

    /**
     * 
     *
     * @param string svgContent
     * @param string name
     * @param SvgElement context
     *
     * @return void
     */
    public function __construct(string $svgContent, string $name, SvgElement $context = null)
    {
        parent::__construct($svgContent, [], $context);
        $this->renameStyle($name);
        $this->removeContents();
    }

>>>>>>> Stashed changes
    /**
     * renameStyle
     *
     * @param  mixed $svgElementName
     * @return self
     */
    public function renameStyle(string $svgElementName): self
    {
        $this->classes = [];
        preg_match_all("/.([a-z0-9]*)({[^}]*})/i", $this->contents(), $comands);
<<<<<<< Updated upstream
        foreach($comands[1] as $k => $class){
            $className = $class . '-' . $svgElementName; 
=======
        foreach ($comands[1] as $k => $class) {
            $className = $class . '-' . $svgElementName;
>>>>>>> Stashed changes
            $this->classes[$className] = $comands[2][$k];
        }
        return $this;
    }
<<<<<<< Updated upstream
    
=======

>>>>>>> Stashed changes
    /**
     * classes get the css classes from style
     *
     * @return array
     */
<<<<<<< Updated upstream
    public function classes() : array
    {
        return $this->classes;
    }
    
=======
    public function classes(): array
    {
        return $this->classes;
    }

>>>>>>> Stashed changes
    /**
     * setClasses set the css classes for style
     *
     * @param  array $classes
     * @return self
     */
<<<<<<< Updated upstream
    public function setClasses(array $classes) : self
=======
    public function setClasses(array $classes): self
>>>>>>> Stashed changes
    {
        $this->classes = $classes;
        return $this;
    }
<<<<<<< Updated upstream
    
=======

>>>>>>> Stashed changes
    /**
     * toHtml
     *
     * @return string
     */
    public function toHtml(): string
    {
        $ret = '<style' . sprintf('%s', $this->renderAttributes()) . ' >' . "\n";
        $classes = $this->classes();
<<<<<<< Updated upstream
        foreach($classes as $className => $comands){
=======
        foreach ($classes as $className => $comands) {
>>>>>>> Stashed changes
            $ret .= '.' . $className . ' ' . $comands . "\n";
        }
        $ret .= '</style>';
        return $ret;
    }
<<<<<<< Updated upstream
    
=======

>>>>>>> Stashed changes
    /**
     * mergeStyles Merge one Style with an other
     *
     * @param  Style $add
     * @return self|null
     */
    public function mergeStyles(Style $add): ?self
    {
<<<<<<< Updated upstream
        if(empty($this->attributes())){
            if(empty($add->attributes())){
                return null;
            }
            foreach($add->attributes() as $name => $arguments){
                $this->setAttribute($name, $arguments);
            }
        }
        
        if(empty($add->attributes())){
            return null;
        }
        $array = array_merge($this->attributes(), $add->attributes());
        foreach($array as $name => $arguments){
=======
        if (empty($this->attributes())) {
            if (empty($add->attributes())) {
                return null;
            }
            foreach ($add->attributes() as $name => $arguments) {
                $this->setAttribute($name, $arguments);
            }
        }

        if (empty($add->attributes())) {
            return null;
        }
        $array = array_merge($this->attributes(), $add->attributes());
        foreach ($array as $name => $arguments) {
>>>>>>> Stashed changes
            $this->setAttribute($name, $arguments);
        }

        return $this;
    }
<<<<<<< Updated upstream
    
=======

>>>>>>> Stashed changes
    /**
     * toArray
     *
     * @return void
     */
    public function toArray()
    {
        $ret = parent::toArray();
        $ret['classes'] = $this->classes();
        return $ret;
    }
}
