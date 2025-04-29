<?php

declare(strict_types=1);

namespace App\Dto\Response;

use DateTimeImmutable;
use MicroPHP\Framework\Dto\Response\BaseRes;
use MicroPHP\Swagger\Schema\Property;
use MicroPHP\Swagger\Schema\Schema;

#[Schema(title: 'book响应参数')]
class BookGetRes extends BaseRes
{
    #[Property(title: 'ID', example: 1)]
    public int $id;

    #[Property(title: '名字', example: 'test')]
    public string $name;

    #[Property(title: '创建时间', example: 1745735933)]
    public int $created_at;

    protected function beforeFill(array &$data): void
    {
        $data['created_at'] = $data['created_at'] instanceof DateTimeImmutable ? $data['created_at']->getTimestamp() : $data['created_at'];
    }
}
