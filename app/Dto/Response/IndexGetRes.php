<?php
declare(strict_types=1);

namespace App\Dto\Response;

use MicroPHP\Data\Data;
use MicroPHP\Swagger\Schema\Property;
use MicroPHP\Swagger\Schema\Schema;

#[Schema(title: 'index响应参数')]
class IndexGetRes extends Data
{
    #[Property(title: 'ID', example: 1)]
    public int $id;

    #[Property(title: '名字', example: 'test')]
    public string $name;
}