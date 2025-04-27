<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Http\Traits;

use League\Route\Http\Exception\MethodNotAllowedException;
use League\Route\Http\Exception\NotFoundException;
use MicroPHP\Framework\Http\Response;
use MicroPHP\Framework\Http\ServerRequest;
use MicroPHP\Framework\Router\Router;
use Psr\Http\Message\ResponseInterface;

trait HttpServerTrait
{
    protected static string $runtimeDir = 'runtime';

    protected function createRuntimeDir(): void
    {
        if (! is_dir(self::$runtimeDir)) {
            mkdir(base_path(self::$runtimeDir));
        }
        if (! is_dir(base_path(self::$runtimeDir . '/logs'))) {
            mkdir(base_path(self::$runtimeDir . '/logs'));
        }
    }

    /** @noinspection PhpRedundantCatchClauseInspection */
    protected function routeDispatch(Router $router, ServerRequest $request): ResponseInterface
    {
        try {
            return $router->dispatch($request);
        } catch (NotFoundException $exception) {
            return new Response(404, [], $exception->getMessage());
        } catch (MethodNotAllowedException $exception) {
            return new Response(405, [], $exception->getMessage());
        }
    }
}
