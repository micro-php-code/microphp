<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Router;

use League\Route\RouteGroup;
use League\Route\Strategy\OptionsHandlerInterface;

class Router extends \League\Route\Router
{
    public function getOrPost(string $path, $handler): RouteGroup
    {
        return $this->group('', function (RouteGroup $group) use ($path, $handler) {
            $group->get($path, $handler);
            $group->post($path, $handler);
        });
    }

    /**
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function map(string $method, string $path, $handler): Route
    {
        $path = sprintf('/%s', ltrim($path, '/'));
        $route = new Route($method, $path, $handler);

        $this->routes[] = $route;

        return $route;
    }

    protected function buildOptionsRoutes(array $options): void
    {
        if (! $this->getStrategy() instanceof OptionsHandlerInterface) {
            return;
        }

        /** @var OptionsHandlerInterface $strategy */
        $strategy = $this->getStrategy();

        foreach ($options as $identifier => $methods) {
            [$scheme, $host, $port, $path] = explode(static::IDENTIFIER_SEPARATOR, $identifier);
            $route = new Route('OPTIONS', $path, $strategy->getOptionsCallable($methods));

            if (! empty($scheme)) {
                $route->setScheme($scheme);
            }

            if (! empty($host)) {
                $route->setHost($host);
            }

            if (! empty($port)) {
                $route->setPort((int) $port);
            }

            $this->routeCollector->addRoute($route->getMethod(), $this->parseRoutePath($route->getPath()), $route);
        }
    }
}
