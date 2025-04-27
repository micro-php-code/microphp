<?php

declare(strict_types=1);

namespace MicroPHP\Framework;

use MicroPHP\Framework\Commands\PublishConfigCommand;
use MicroPHP\Framework\Commands\StartCommand;
use MicroPHP\Framework\Commands\SwaggerGenerateCommand;
use MicroPHP\Framework\Config\ConfigProviderInterface;

class ConfigProvider implements ConfigProviderInterface
{
    public function config(): array
    {
        return [
            'commands' => [
                StartCommand::class,
                PublishConfigCommand::class,
                SwaggerGenerateCommand::class,
            ],
        ];
    }
}
