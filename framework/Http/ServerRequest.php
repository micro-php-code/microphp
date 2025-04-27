<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Http;

use Amp\ByteStream\BufferException;
use Amp\ByteStream\StreamException;
use Amp\Http\Server\ClientException;
use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Uri;
use InvalidArgumentException;
use MicroPHP\Framework\Http\Traits\InputTrait;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Swoole\Http\Request;

class ServerRequest extends \GuzzleHttp\Psr7\ServerRequest implements ServerRequestInterface
{
    use InputTrait;

    private ServerRequestInterface $bind;

    public static function fromPsr7(ServerRequestInterface $request): ServerRequest|ServerRequestInterface
    {
        return (new static($request->getMethod(), $request->getUri(), $request->getHeaders(), $request->getBody(), $request->getProtocolVersion(), $request->getServerParams()))
            ->withParsedBody(self::normalizeParsedBody($request->getParsedBody(), $request))
            ->withUploadedFiles($request->getUploadedFiles())
            ->withCookieParams($request->getCookieParams())
            ->withQueryParams($request->getQueryParams());
    }

    public static function fromSwoole(Request $swooleRequest): ServerRequest|ServerRequestInterface
    {
        $server = $swooleRequest->server;
        $method = $server['request_method'] ?? 'GET';
        $headers = $swooleRequest->header ?? [];
        $uri = self::getUriFromSwooleRequest($swooleRequest);
        $httpFactory = new HttpFactory();
        $body = $httpFactory->createStream((string) $swooleRequest->rawContent());
        $protocol = isset($server['server_protocol']) ? str_replace('HTTP/', '', $server['server_protocol']) : '1.1';
        $request = new ServerRequest($method, $uri, $headers, $body, $protocol, $server);

        return $request->withCookieParams($swooleRequest->cookie ?? [])
            ->withQueryParams($swooleRequest->get ?? [])
            ->withParsedBody(self::normalizeParsedBody($swooleRequest->post ?? [], $request))
            ->withUploadedFiles(self::normalizeFiles($swooleRequest->files ?? []));
    }

    /**
     * @throws ClientException
     * @throws BufferException
     * @throws StreamException
     */
    public static function fromAmp(\Amp\Http\Server\Request $ampRequest): ServerRequest|ServerRequestInterface
    {
        $request = new ServerRequest(
            $ampRequest->getMethod(),
            $ampRequest->getUri(),
            $ampRequest->getHeaders(),
            $ampRequest->getBody()->buffer(),
            $ampRequest->getProtocolVersion(),
        );

        return $request->withParsedBody(self::normalizeParsedBody([], $request))
            ->withCookieParams($ampRequest->getCookies())
            ->withQueryParams($ampRequest->getQueryParameters());
    }

    protected static function normalizeParsedBody(array $data = [], ?RequestInterface $request = null): ?array
    {
        if (! $request) {
            return $data;
        }

        $rawContentType = $request->getHeaderLine('content-type');
        if (($pos = strpos($rawContentType, ';')) !== false) {
            $contentType = strtolower(substr($rawContentType, 0, $pos));
        } else {
            $contentType = strtolower($rawContentType);
        }
        switch ($contentType) {
            case 'application/json':
            case 'text/json':
                $data = json_decode((string) $request->getBody(), true);
                break;
            case 'application/xml':
            case 'text/xml':
                $data = (array) simplexml_load_string((string) $request->getBody());
                break;
        }

        return $data;
    }

    private static function getUriFromSwooleRequest(Request $swooleRequest): UriInterface
    {
        $server = $swooleRequest->server;
        $header = $swooleRequest->header;
        $uri = new Uri();
        $uri = $uri->withScheme(! empty($server['https']) && $server['https'] !== 'off' ? 'https' : 'http');

        $hasPort = false;
        if (isset($server['http_host'])) {
            [$host, $port] = self::parseHost($server['http_host']);
            $uri = $uri->withHost($host);
            if (isset($port)) {
                $hasPort = true;
                $uri = $uri->withPort($port);
            }
        } elseif (isset($server['server_name'])) {
            $uri = $uri->withHost($server['server_name']);
        } elseif (isset($server['server_addr'])) {
            $uri = $uri->withHost($server['server_addr']);
        } elseif (isset($header['host'])) {
            $hasPort = true;
            [$host, $port] = self::parseHost($header['host']);
            if (isset($port) && $port !== self::getUriDefaultPort($uri)) {
                $uri = $uri->withPort($port);
            }

            $uri = $uri->withHost($host);
        }

        if (! $hasPort && isset($server['server_port'])) {
            $uri = $uri->withPort($server['server_port']);
        }

        $hasQuery = false;
        if (isset($server['request_uri'])) {
            $requestUriParts = explode('?', $server['request_uri']);
            $uri = $uri->withPath($requestUriParts[0]);
            if (isset($requestUriParts[1])) {
                $hasQuery = true;
                $uri = $uri->withQuery($requestUriParts[1]);
            }
        }

        if (! $hasQuery && isset($server['query_string'])) {
            $uri = $uri->withQuery($server['query_string']);
        }

        return $uri;
    }

    /**
     * Get host parts, support ipv6.
     */
    private static function parseHost(string $httpHost): array
    {
        $parts = parse_url('//' . $httpHost);
        if (! isset($parts['host'])) {
            throw new InvalidArgumentException('Invalid host: ' . $httpHost);
        }

        return [$parts['host'], $parts['port'] ?? null];
    }

    private static function getUriDefaultPort(UriInterface $uri): ?int
    {
        return $uri->getScheme() === 'https' ? 443 : ($uri->getScheme() === 'http' ? 80 : null);
    }
}
