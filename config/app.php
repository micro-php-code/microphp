<?php

declare(strict_types=1);

use MicroPHP\Framework\Env;
use MicroPHP\Framework\Http\Enum\Driver;

return [
    'app_name' => Env::get('APP_NAME', 'MicroPHP'),
    'app_env' => Env::get('APP_ENV', 'local'),
    'scanner' => [
        'directories' => [
            'app',
            'vendor/microphp/framework/src',
        ],
    ],
    'server' => [
        'driver' => Env::get('SERVER_DRIVER', Driver::WORKERMAN),
        'host' => '0.0.0.0',
        'port' => 8080,
        'workers' => 8,
    ],
];
