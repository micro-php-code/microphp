<?php

declare(strict_types=1);

use MicroPHP\Framework\Application;

/** @var Application $application */
$application = require_once 'bootstrap.php';
/** @noinspection PhpUnhandledExceptionInspection */
$application->run();
