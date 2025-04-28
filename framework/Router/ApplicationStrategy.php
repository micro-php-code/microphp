<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Router;

use League\Route\Route;
use MicroPHP\Framework\Dto\Request\BaseReq;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionClass;
use ReflectionException;

class ApplicationStrategy extends \League\Route\Strategy\ApplicationStrategy
{
    private static array $cache = [];

    /**
     * @throws ReflectionException
     */
    public function invokeRouteCallable(\MicroPHP\Framework\Router\Route|Route $route, ServerRequestInterface $request): ResponseInterface
    {
        $controller = $route->getCallable($this->getContainer());
        $key = $route->getMethod() . '::' . $route->getPath();

        if (static::$cache[$key] ?? false) {
            $response = $controller(static::$cache[$key]::fromRequest($request), $route->getVars());
            return $this->decorateResponse($response);
        }

        $handler = $route->getHandler();
        if (is_array($handler) && isset($handler[0]) && class_exists($handler[0])) {
            $reflection = new ReflectionClass($handler[0]);
            $methodReflection = $reflection->getMethod($handler[1]);
            foreach ($methodReflection->getParameters() as $parameter) {
                $paramClass = $parameter->getType()?->getName() ?? '';
                if (class_exists($paramClass) && is_subclass_of($paramClass, BaseReq::class)) {
                    static::$cache[$key] = $paramClass;
                    $response = $controller($paramClass::fromRequest($request), $route->getVars());
                    return $this->decorateResponse($response);
                }
            }
        }

        $response = $controller($request, $route->getVars());
        return $this->decorateResponse($response);
    }
}
