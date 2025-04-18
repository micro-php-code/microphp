<?php

declare(strict_types=1);

namespace App\Util;

use App\Enum\StatusEnum;
use JsonSerializable;

class JsonResponse implements JsonSerializable
{
    public StatusEnum $code;

    public string $message;

    public mixed $data;

    public function __construct(StatusEnum $code, string $message, mixed $data)
    {
        $this->code = $code;
        $this->message = $message;
        $this->data = $data;
    }

    public function __toString(): string
    {
        return json_encode($this->jsonSerialize());
    }

    public static function success(mixed $data = null, StatusEnum $code = StatusEnum::SUCCESS, string $message = ''): JsonResponse
    {
        return new JsonResponse($code, $message ?: $code->label(), $data);
    }

    public static function error(string $message = '', StatusEnum $code = StatusEnum::ERROR, mixed $data = null): JsonResponse
    {
        return new JsonResponse($code, $message ?: $code->label(), $data);
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'code' => $this->code->value,
            'message' => $this->message,
            'data' => $this->data,
        ];
    }
}
