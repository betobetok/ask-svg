<?php

declare(strict_types=1);

namespace BladeUI\Icons\Configurators;

/**
 * Filter
 */
class Filter extends Configurator
{
    public function __construct(string $contents, array $attributes = [], $context = null)
    {
        foreach ($attributes as $key => $attribute) {
            $this->$key($attribute);
        }

        parent::__construct($contents,  $attributes, $context);
    }

    /**
     * getAllElements
     *
     * @return void
     */
    public function getAllElements(): void
    {
        
        while (strlen($this->contents) > 0) {
            if(empty($this->contents)){
                break;
            }
            preg_match("/<fe([^\/][^>\s]*)([^>\/]*)(\/?)>/i", $this->contents, $tag);
            if (empty($tag)) {
                break;
            }
            $name = $tag[1];
            $attributes = $this->getElementAttributes($tag[0]);
            
            $ret = new FeEfect($name, '', $attributes, $this);
            $this->setElement('fe'.$name, $ret);
            $this->contents = trim(str_replace($tag[0], '', $this->contents));
        }
    }
}
