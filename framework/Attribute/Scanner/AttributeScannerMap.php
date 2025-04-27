<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Attribute\Scanner;

use MicroPHP\Framework\Application;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class AttributeScannerMap
{
    private array $map = [];

    public function add(string $className, string $attributeClassName, array $arguments): void
    {
        $this->map[$attributeClassName][$className] = $arguments;
    }

    public function getClassAttributeArguments(string $class, string $attributeClassName): ?array
    {
        return $this->map[$attributeClassName][$class] ?? null;
    }

    public function getClassesNames(string $attributeClassName): array
    {
        return array_keys($this->map[$attributeClassName] ?? []);
    }

    public function classHasAttribute(string $class, string $attributeClassName): bool
    {
        return isset($this->map[$attributeClassName][$class]);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function get(): AttributeScannerMap
    {
        return Application::getClass(self::class);
    }
}
