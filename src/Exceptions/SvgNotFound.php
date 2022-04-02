<?php

declare(strict_types=1);

namespace ASK\Svg\Exceptions;

use Exception;

final class SvgNotFound extends Exception
{
    public static function missing(string $set, string $name): self
    {
        return new self("Svg by name \"$name\" from set \"$set\" not found.");
    }

    public static function pathNotExist(string $path): self
    {
        return new self("Path \"" . $path . "\" not found or does not have sufficient permissions.");
    }
}
