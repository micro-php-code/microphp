<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Router;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;

class Route extends \League\Route\Route
{
    public function getHandler(): array|callable|object|string
    {
        return $this->handler;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    protected function resolve(string $class, ?ContainerInterface $container = null)
    {
        if ($container instanceof \MicroPHP\Framework\Container\ContainerInterface) {
            return $this->resolveClass($class, $container);
        }

        if (class_exists($class)) {
            return new $class();
        }

        return $class;
    }

    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function resolveClass(string $class, \MicroPHP\Framework\Container\ContainerInterface $container)
    {
        if ($container->has($class)) {
            return $container->get($class);
        }
        $reflection = new ReflectionClass($class);
        $params = [];
        foreach ($reflection->getConstructor()?->getParameters() ?? [] as $parameter) {
            if ($parameter->getType() instanceof ReflectionNamedType) {
                $type = $parameter->getType()->getName();
                if (class_exists($type)) {
                    $params[] = $this->resolveClass($type, $container);
                }
            }
        }
        $instance = $reflection->newInstanceArgs($params);
        $container->addShared($class, $instance);
        return $instance;
    }
}
