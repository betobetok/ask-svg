<?php

declare(strict_types=1);

namespace BladeUI\Icons\Configurators;

<<<<<<< Updated upstream
use BladeUI\Icons\Concerns\RendersAttributes;
use NumPHP\Core\NumArray;
=======
use BladeUI\Icons\Conteiner;
>>>>>>> Stashed changes

/**
 * G
 */
<<<<<<< Updated upstream
class G extends Configurator
{

    /** @var bool isTransformable */
    protected bool $isTransformable = true;

    public function __construct(string $name, string $contents, array $attributes = [], $context = null)
    {
        parent::__construct($name,  $contents,  $attributes, $context);
=======
class G extends Configurator implements Conteiner
{
    public function __construct(string $contents, array $attributes = [], $context = null)
    {
        $this->isTransformable = true;

        parent::__construct($contents,  $attributes, $context);

        $this->cleanContent();
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
>>>>>>> Stashed changes
    }
}
