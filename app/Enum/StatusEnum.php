<?php

declare(strict_types=1);

namespace App\Enum;

use MicroPHP\Contract\Enum\Attributes\EnumClass;
use MicroPHP\Contract\Enum\Attributes\EnumProperty;
use MicroPHP\Contract\Enum\EnumHelper;

#[EnumClass(name: '状态码枚举类')]
enum StatusEnum: int
{
    use EnumHelper;

    #[EnumProperty(label: 'success')]
    case SUCCESS = 200;

    #[EnumProperty(label: 'bad request')]
    case BAD_REQUEST = 400;

    #[EnumProperty(label: 'system error')]
    case ERROR = 500;
}
