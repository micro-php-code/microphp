<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Http\Enum;

class Driver
{
    public const WORKERMAN = 'workerman';

    public const ROADRUNNER = 'roadrunner';

    public const SWOOLE = 'swoole';

    public const AMP = 'amp';
}
