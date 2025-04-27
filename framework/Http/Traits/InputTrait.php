<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Http\Traits;

trait InputTrait
{
    public function input(string $key, $default = null): mixed
    {
        return $this->all()[$key] ?? $default;
    }

    public function all(): array
    {
        return array_replace_recursive($this->getQueryParams(), $this->getParsedBody() ?: []);
    }
}
