<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Request\IndexGetReq;
use App\Entity\Book;
use App\Repository\BookRepository;

class BookService
{
    public function __construct(
        private readonly BookRepository $bookRepository
    ) {}

    public function get(IndexGetReq $param): ?Book
    {
        return $this->bookRepository->findByPK($param->id);
    }
}
