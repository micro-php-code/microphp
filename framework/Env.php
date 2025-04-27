<?php

declare(strict_types=1);

namespace MicroPHP\Framework;

use Symfony\Component\Dotenv\Dotenv;

class Env
{
    public static function get(string $key, mixed $default = null)
    {
        $value = getenv($key);
        if ($value === false) {
            return $default;
        }
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }
        if (($valueLength = strlen($value)) > 1 && $value[0] === '"' && $value[$valueLength - 1] === '"') {
            return substr($value, 1, -1);
        }

        return $value;
    }

    public static function load(): void
    {
        (new Dotenv())->usePutenv()->loadEnv(base_path('.env'));
    }
}
