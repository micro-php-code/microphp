<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Testing;

use PHPUnit\Framework\Assert;
use Psr\Http\Message\ResponseInterface;

class HttpTestResponse
{
    private ResponseInterface $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function assertStatus(int $status): self
    {
        Assert::assertSame($status, $this->response->getStatusCode());

        return $this;
    }

    public function assertOk(): self
    {
        return $this->assertStatus(200);
    }

    /**
     * 判断 JSON 响应数据中是否存在指定的键，支持通配符*
     *
     * @param string $key 要判断的键名数组
     */
    public function assertJsonHasKey(string $key): self
    {
        $default = '__ASSERT_NOT_FOUND__';
        $json = $this->decodeResponseJson();
        $result = data_get($json, $key, $default);
        if (str_contains($key, '*')) {
            if (count(array_filter($result, fn ($item) => ! is_null($item))) === 0) {
                Assert::fail('array key:' . $key . ' not found');
            }
        } else {
            Assert::assertNotEquals($default, $result, 'array key:' . $key . ' not found');
        }

        return $this;
    }

    public function json(?string $key = null)
    {
        return is_null($key) ? $this->decodeResponseJson() : $this->decodeResponseJson()[$key];
    }

    public function getContent(): string
    {
        return $this->response->getBody()->getContents();
    }

    private function decodeResponseJson()
    {
        $testJson = $this->response->getBody()->getContents();

        return json_decode($testJson, true);
    }
}
