<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Config;

interface ConfigProviderInterface
{
    public function config(): array;
}
