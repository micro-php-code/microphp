<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Http\Contract;

use MicroPHP\Framework\Router\Router;

interface HttpServerInterface
{
    public function run(Router $router): void;
}
