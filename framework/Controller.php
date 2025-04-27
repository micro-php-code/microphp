<?php

declare(strict_types=1);

namespace MicroPHP\Framework;

use JsonSerializable;
use MicroPHP\Framework\Http\Response;

class Controller
{
    protected function json(mixed $data): Response
    {
        if ($data instanceof JsonSerializable) {
            $result = $data->jsonSerialize();
        } else {
            $result = $data;
        }
        if (is_array($result)) {
            $result = json_encode($result);
        }

        return new Response(200, ['Content-Type' => 'application/json; charset=utf-8'], $result);
    }
}
