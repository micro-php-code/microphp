<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Testing;

use JsonException;
use MicroPHP\Framework\Application;
use ReflectionException;
use Throwable;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    use HttpTestTrait;

    /**
     * @throws ReflectionException
     * @throws JsonException
     * @throws Throwable
     */
    protected function setUp(): void
    {
        Application::boot();
        parent::setUp();
    }
}
