<?php

declare(strict_types=1);

use MicroPHP\Framework\Application;

require __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set('Asia/Shanghai');

! defined('BASE_PATH') && define('BASE_PATH', __DIR__ . '/../');

/** @noinspection PhpUnhandledExceptionInspection */
$app = Application::boot();

return $app;