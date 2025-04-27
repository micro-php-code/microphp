<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Testing;

use MicroPHP\Framework\Utils\Http;

trait HttpTestTrait
{
    protected function get(string $uri, array $requestParams = [], array $headers = []): HttpTestResponse
    {
        return $this->request('GET', $uri, [], $headers, $requestParams);
    }

    protected function post(string $uri, array $data = [], array $headers = [], array $requestParams = []): HttpTestResponse
    {
        return $this->request('POST', $uri, $data, $headers, $requestParams);
    }

    protected function request(string $method, string $uri, array $data = [], array $headers = [], array $requestParams = []): HttpTestResponse
    {
        return new HttpTestResponse((new Http())->request($method, $uri, requestParams: $requestParams, json: $data, headers: $headers));
    }
}
