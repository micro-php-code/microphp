<?php
declare(strict_types=1);

namespace App\Dto\Request;

use MicroPHP\Data\Data;
use MicroPHP\Swagger\Schema\Property;
use MicroPHP\Swagger\Schema\Schema;

#[Schema(title: 'index请求参数')]
class IndexGetReq extends Data
{
    #[Property(title: 'ID', example: 1)]
    public int $id = 1;
}