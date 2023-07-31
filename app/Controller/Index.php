<?php

declare(strict_types=1);
namespace App\Controller;
use Simple\Framework\Controller;
use Simple\Framework\Http\Response;
use Simple\Framework\Http\ServerRequest;

class Index extends Controller
{
    public function index(ServerRequest $request): Response
    {
        return $this->json('Hello World');
    }
}