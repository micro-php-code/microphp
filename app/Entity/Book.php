<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\ORM\Entity\Behavior;
use DateTimeImmutable;
use MicroPHP\Data\Data;
use MicroPHP\Framework\Database\Cast\IntTimeTypeCast;

#[Entity(table: 'book', typecast: [
    IntTimeTypeCast::class,
])]
#[Behavior\CreatedAt(field: 'created_at')]
#[Behavior\UpdatedAt(field: 'updated_at')]
class Book extends Data
{
    #[Column(type: 'primary')]
    public int $id;

    #[Column(type: 'string')]
    public string $name;

    #[Column(type: 'datetime', typecast: IntTimeTypeCast::class)]
    public DateTimeImmutable $created_at;

    #[Column(type: 'datetime', typecast: IntTimeTypeCast::class)]
    public DateTimeImmutable $updated_at;
}
