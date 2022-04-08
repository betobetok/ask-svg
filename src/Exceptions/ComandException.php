<?php

declare(strict_types=1);

namespace ASK\Svg\Exceptions;

use Exception;

/**
 * ComandException
 */
final class ComandException extends Exception
{
    /**
     * configuration
     *
     * @param  string $comand
     * @param  int $count
     * @param  int $expected
     * @return self
     */
    public static function configuration(string $comand, int $count, int $expected): self
    {
        if ($expected === 1) {
            return new self("Incorrect configuration of attributes: a command " . $comand . " expects at least 1 parameter");
        } elseif ($expected === 0) {
            return new self("Incorrect configuration of attributes: a command " . $comand . " dont expect any parameter");
        }
        return new self("Incorrect configuration of attributes: a command " . $comand . " expects parameters in groups of " . $expected . ", " . $count . "parameters received");
    }

    /**
     * pointNotFound
     *
     * @param  mixed $wanted
     * @param  mixed $permited
     * @return void
     */
    public static function pointNotFound($wanted, $permited)
    {
        return new self("Point " . $wanted . " doesn't exist, maximum position :" . $permited);
    }
}
