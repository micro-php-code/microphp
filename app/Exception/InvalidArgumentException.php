<?php

declare(strict_types=1);

namespace App\Exception;

class InvalidArgumentException extends \InvalidArgumentException
{
    public function __construct(
        string $message = 'invalid argument',
    ) {
        parent::__construct($message);
    }
}
