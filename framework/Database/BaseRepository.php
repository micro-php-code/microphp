<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Database;

use Cycle\ORM\ORMInterface;
use Cycle\ORM\Select;
use Cycle\ORM\Select\Repository;
use MicroPHP\Framework\Application;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

abstract class BaseRepository extends Repository
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(
    ) {
        $select = new Select(Application::getContainer()->get(ORMInterface::class), $this->getEntityClassName());
        parent::__construct($select);
    }

    abstract public function getEntityClassName(): string;
}
