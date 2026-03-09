<?php

namespace Ehann\RedisRaw\Exceptions;

class RedisRawCommandException extends \Exception
{
    public function __construct($message = '', $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct(trim("Redis Raw Command Failed. $message"), $code, $previous);
    }
}
