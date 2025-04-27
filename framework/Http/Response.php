<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Http;

use Psr\Http\Message\ResponseInterface;

class Response extends \GuzzleHttp\Psr7\Response implements ResponseInterface {}
