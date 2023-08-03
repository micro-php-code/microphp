<?php

declare(strict_types=1);

use MicroPHP\Framework\Env;

return [
    'app_name' => Env::get('APP_NAME', 'MicroPHP'),
    'app_env' => Env::get('APP_ENV', 'local'),
    'app_url' => Env::get('APP_URL', 'http://127.0.0.1:8080'),
    'scanner' => [
        'directories' => [
            'app',
            'vendor/microphp/framework/src',
        ],
    ],
];
