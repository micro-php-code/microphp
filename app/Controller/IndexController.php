<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Request\IndexGetReq;
use App\Dto\Response\IndexGetRes;
use MicroPHP\Framework\Controller;
use MicroPHP\Framework\Http\Response;
use MicroPHP\Framework\Http\ServerRequest;
use MicroPHP\Swagger\Schema\Get;
use MicroPHP\Swagger\Schema\Post;
use MicroPHP\Swagger\Schema\RequestBody;
use MicroPHP\Swagger\Schema\SuccessJsonResponse;
use OpenApi\Attributes\Info;
use OpenApi\Attributes\OpenApi;
use OpenApi\Attributes\Server;

#[OpenApi(
    info: new Info(version: '1.0', title: 'micro-php'),
    servers: [new Server(url: 'http://127.0.0.1:8080')]
)]
class IndexController extends Controller
{
    public const TAG = 'Index';

    #[Get(summary: '首页', tags: [self::TAG])]
    #[SuccessJsonResponse(ref: IndexGetRes::class)]
    public function index(ServerRequest $request): Response
    {
        return $this->json('Hello World');
    }

    #[Post(summary: '获取', tags: [self::TAG])]
    #[RequestBody(ref: IndexGetReq::class)]
    #[SuccessJsonResponse(ref: IndexGetRes::class)]
    public function get(ServerRequest $request): Response
    {
        return $this->json(new IndexGetRes(['id' => $request->input('id'), 'name' => 'hello']));
    }
}
