<?php

declare(strict_types=1);

namespace Tests\Feature\Controller;


use League\Route\Route;
use League\Route\Router;
use MicroPHP\Framework\Application;
use MicroPHP\Framework\Testing\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class IndexTest extends TestCase
{
    public function test_index()
    {
        $result = $this->get('http://127.0.0.1:8080')->assertOk();
        $this->assertSame('Hello World', $result->getContent());
    }
}
