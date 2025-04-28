<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Request\BookGetReq;
use App\Dto\Response\BookGetRes;
use App\Exception\InvalidArgumentException;
use App\Service\BookService;
use App\Util\JsonResponse;
use MicroPHP\Framework\Controller;
use MicroPHP\Framework\Http\Response;
use MicroPHP\Swagger\Schema\Get;
use MicroPHP\Swagger\Schema\Post;
use MicroPHP\Swagger\Schema\RequestBody;
use MicroPHP\Swagger\Schema\SuccessJsonResponse;
use OpenApi\Attributes\Info;
use OpenApi\Attributes\OpenApi;
use OpenApi\Attributes\Server;
use Psr\Http\Message\ServerRequestInterface;

#[OpenApi(
    info: new Info(version: '1.0', title: 'micro-php'),
    servers: [new Server(url: 'http://127.0.0.1:8080')]
)]
class BookController extends Controller
{
    public const TAG = 'Index';

    public function __construct(
        private readonly BookService $bookService
    ) {}

    #[Get(summary: '首页', tags: [self::TAG])]
    #[SuccessJsonResponse(ref: BookGetRes::class)]
    public function index(ServerRequestInterface $request): Response
    {
        return $this->json('Hello World - ' . $request->getUri()->getPath());
    }

    #[Post(summary: '获取', tags: [self::TAG])]
    #[RequestBody(ref: BookGetReq::class)]
    #[SuccessJsonResponse(ref: BookGetRes::class)]
    public function get(BookGetReq $param): Response
    {
        $result = $this->bookService->get($param);
        if (! $result) {
            throw new InvalidArgumentException();
        }
        return $this->json(JsonResponse::success(BookGetRes::from($result)));
    }
}
