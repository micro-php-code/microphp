<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Dto\Response;

use MicroPHP\Data\Data;
use MicroPHP\Framework\Dto\ConstraintsTrait;

class BaseRes extends Data
{
    use ConstraintsTrait;
}
