<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Http;

use MicroPHP\Framework\Config\Config;

class ServerConfig
{
    private array $config;

    public function __construct()
    {
        $this->config = Config::get('app.server');
    }

    public function getWorkers(): int
    {
        return $this->config['workers'];
    }

    public function getHost(): string
    {
        return $this->config['host'];
    }

    public function getPort(): int
    {
        return $this->config['port'];
    }

    public function getUri(bool $withSchema = false): string
    {
        return ($withSchema ? 'http://' : '') . $this->getHost() . ($this->getPort() ? ':' . $this->getPort() : '');
    }

    public function getDriver(): string
    {
        return $this->config['driver'];
    }
}
