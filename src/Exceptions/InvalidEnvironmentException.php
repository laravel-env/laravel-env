<?php

declare(strict_types=1);

namespace LaravelEnv\LaravelEnv\Exceptions;

use Exception;

class InvalidEnvironmentException extends Exception
{
    public function __construct($message = 'Your current environment variables are not valid.', $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
