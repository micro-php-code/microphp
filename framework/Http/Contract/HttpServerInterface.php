<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Http\Contract;

use MicroPHP\Framework\Router\Router;
use Symfony\Component\Console\Output\OutputInterface;

interface HttpServerInterface
{
    public function run(Router $router, OutputInterface $output): void;
}
