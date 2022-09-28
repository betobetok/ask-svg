<?php

declare(strict_types=1);

namespace ASK\Svg\Exceptions;

use Exception;

final class TransformException extends Exception
{
    public static function index(int $index): self
    {
        return new self("Element don't habe a transformation in index $index.");
    }
}
