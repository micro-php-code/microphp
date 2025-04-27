<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Swagger;

use MicroPHP\Framework\Application;
use MicroPHP\Framework\Router\Router;
use MicroPHP\Swagger\Contract\PathGetterInterface;
use MicroPHP\Swagger\Enum\MethodEnum;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class PathGetter implements PathGetterInterface
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function getPath(string $controller, string $function, ?MethodEnum $requestMethod = null): ?string
    {
        /** @var Router $route */
        $route = Application::getContainer()->get(Router::class);
        foreach ($route->getRoutes() as $route) {
            $handler = $route->getHandler();
            if (is_array($handler)) {
                if ($controller === $handler[0] && $function === $handler[1]) {
                    return $route->getPath();
                }
            }
        }

        return null;
    }
}
