<?php

declare(strict_types=1);

namespace ASK\Svg\Configurators;

use ASK\Svg\Conteiner;

/**
 * A group element "g" in a svg document
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
     *
     * @return array
     */
    public function getContent(): array
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
     * @param  mixed $content
     * @return self
     */
    public function setContent($content): self
    {
        $this->content = $content;

        return $this;
    }
}
