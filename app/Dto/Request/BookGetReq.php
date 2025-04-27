<?php

declare(strict_types=1);

namespace App\Dto\Request;

use MicroPHP\Framework\Dto\Request\BaseReq;
use MicroPHP\Swagger\Schema\Property;
use MicroPHP\Swagger\Schema\Schema;
use Symfony\Component\Validator\Constraints\Positive;

#[Schema(title: 'book请求参数')]
class BookGetReq extends BaseReq
{
    #[Property(title: 'ID', example: 1)]
    #[Positive(message: 'ID必须为正整数')]
    public int $id;
}
