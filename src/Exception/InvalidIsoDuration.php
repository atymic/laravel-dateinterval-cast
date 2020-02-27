<?php

declare(strict_types=1);

namespace Atymic\DateIntervalCast\Exception;

use Exception;

class InvalidIsoDuration extends DateIntervalCastException
{
    public static function make(string $duration, Exception $previous): self
    {
        return new self(
            sprintf('`%s` is not a valid ISO 8601 duration', $duration),
            $previous->getCode(),
            $previous
        );
    }
}
