<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Logger\Enum;

enum LoggerHandler: string
{
    /**
     * 标准输出处理器
     */
    case STDOUT = 'STDOUT';

    /**
     * 文件处理器
     */
    case ROTATING_FILE = 'ROTATING_FILE';
}
