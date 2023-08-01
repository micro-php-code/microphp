<?php

declare(strict_types=1);
namespace App\Controller;
use MicroPHP\Framework\Controller;
use MicroPHP\Framework\Http\Response;
use MicroPHP\Framework\Http\ServerRequest;

class Index extends Controller
{
    public function index(ServerRequest $request): Response
    {
        return $this->json('Hello World');
    }
}