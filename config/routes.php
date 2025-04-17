<?php

declare(strict_types=1);

use App\Controller\IndexController;
use App\Middleware\NothingMiddleware;
use MicroPHP\Framework\Http\Response;
use MicroPHP\Framework\Router\Router;

$router = new Router();

$router->get('/', [IndexController::class, 'index'])->middleware(new NothingMiddleware());
$router->post('/get', [IndexController::class, 'get'])->middleware(new NothingMiddleware());

$router->get('/favicon.ico', function () {
    return new Response(200, [], '');
});

return $router;
