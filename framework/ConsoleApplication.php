<?php

declare(strict_types=1);

namespace MicroPHP\Framework;

use MicroPHP\Framework\Attribute\Attributes\CMD;
use MicroPHP\Framework\Attribute\Scanner\AttributeScannerMap;
use MicroPHP\Framework\Config\Config;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;

class ConsoleApplication
{
    /**
     * @throws ContainerExceptionInterface
     * @throws Throwable
     * @throws NotFoundExceptionInterface
     */
    public static function boot(Application $app): void
    {
        $configCommands = Config::get('commands');
        $application = new \Symfony\Component\Console\Application();
        $application->addCommands(array_map(static function (string $command) {
            return new $command();
        }, array_unique(array_merge($configCommands, $app->getClass(AttributeScannerMap::class)::get()->getClassesNames(CMD::class)))));

        $application->run();
    }
}
