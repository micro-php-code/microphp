<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Utils;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class Http
{
    /**
     * @throws GuzzleException
     */
    public function get(string $url, array $requestParams = [], array $json = [], array $headers = [], array $formParams = []): ResponseInterface
    {
        return $this->request('GET', $url, $requestParams, $json, $headers, $formParams);
    }

    /**
     * @throws GuzzleException
     */
    public function post(string $url, array $requestParams = [], array $json = [], array $headers = [], array $formParams = []): ResponseInterface
    {
        return $this->request('POST', $url, $requestParams, $json, $headers, $formParams);
    }

    /**
     * @throws GuzzleException
     */
    public function request(string $method, string $url, array $requestParams = [], array $json = [], array $headers = [], array $formParams = []): ResponseInterface
    {
        $client = new Client();
        if ($requestParams) {
            $url = $url . '?' . http_build_query($requestParams);
        }

        return $client->request($method, $url, [
            'headers' => $headers,
            'form_params' => $formParams,
            'json' => $json,
        ]);
    }
}
