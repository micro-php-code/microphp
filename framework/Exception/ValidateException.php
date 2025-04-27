<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Exception;

use Exception;

class ValidateException extends Exception
{
    public function __construct(string $message, int $code = 400)
    {
        parent::__construct($message, $code);
    }
}
