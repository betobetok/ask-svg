<?php

declare(strict_types=1);

namespace BladeUI\Icons\Configurators;

use BladeUI\Icons\Conteiner;

/**
 * G
 */
class G extends Configurator implements Conteiner
{
    public function __construct(string $contents, array $attributes = [], $context = null)
    {
        $this->isTransformable = true;

        foreach ($attributes as $key => $attribute) {
            $this->$key($attribute);
        }

        parent::__construct($contents, $attributes, $context);
    }

    /**
     * Get the value of content
     */
    public function getContent()
    {
        $vars = get_object_vars($this);
        $att = array_keys(get_class_vars(__CLASS__));
        $ret = [];
        foreach ($vars as $k => $prop) {
            if (!in_array($k, $att)) {
                $ret[] = $prop;
            }
        }

        return $ret;
    }

    /**
     * Set the value of content
     *
     * @return  self
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }
}
