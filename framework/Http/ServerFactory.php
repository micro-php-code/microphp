<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Http;

use MicroPHP\Amp\AmpHttpServer;
use MicroPHP\Framework\Http\Contract\HttpServerInterface;
use MicroPHP\Framework\Http\Enum\Driver;
use MicroPHP\RoadRunner\RoadRunnerHttpServer;
use MicroPHP\Swoole\SwooleHttpServer;
use MicroPHP\Workerman\WorkermanHttpServer;
use RuntimeException;

class ServerFactory
{
    public static function newServer(): HttpServerInterface
    {
        $serverConfig = new ServerConfig();

        return match ($serverConfig->getDriver()) {
            Driver::WORKERMAN => new WorkermanHttpServer(),
            Driver::ROADRUNNER => new RoadRunnerHttpServer(),
            Driver::SWOOLE => new SwooleHttpServer(),
            Driver::AMP => new AmpHttpServer(),
            default => throw new RuntimeException('unsupported driver: ' . $serverConfig->getDriver()),
        };
    }
}
