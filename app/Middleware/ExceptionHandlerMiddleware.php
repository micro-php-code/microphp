<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Enum\StatusEnum;
use App\Exception\InvalidArgumentException;
use App\Util\JsonResponse;
use MicroPHP\Framework\Exception\ValidateException;
use MicroPHP\Framework\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ExceptionHandlerMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (ValidateException|InvalidArgumentException $e) {
            return new Response(status: 200, headers: ['Content-Type' => 'application/json; charset=utf-8'], body: JsonResponse::error($e->getMessage(), StatusEnum::BAD_REQUEST));
        }
    }
}
