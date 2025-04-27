<?php

declare(strict_types=1);

namespace App\Dto\Response;

use DateTimeImmutable;
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

    #[Property(title: '创建时间', example: 1745735933)]
    public DateTimeImmutable $created_at;

    public function toArray(): array
    {
        $result = parent::toArray();
        $result['created_at'] = $this->created_at->getTimestamp();
        return $result;
    }
}
