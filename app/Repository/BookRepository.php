<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Book;
use MicroPHP\Framework\Database\BaseRepository;

class BookRepository extends BaseRepository
{
    public function getEntityClassName(): string
    {
        return Book::class;
    }
}
