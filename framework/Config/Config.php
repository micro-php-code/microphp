<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Config;

use ReflectionException;

class Config
{
    private static array $config = [];

    public static function get(string $key, $default = null)
    {
        $keys = explode('.', $key);
        $config = self::$config;
        foreach ($keys as $key) {
            if (isset($config[$key])) {
                $config = $config[$key];
            } else {
                return $default;
            }
        }

        return $config;
    }

    /**
     * @throws ReflectionException
     */
    public static function load(string $directory): array
    {
        static::$config = (new ConfigProviderScanner())->scan();

        $files = glob($directory . '/*.php');
        foreach ($files as $file) {
            $key = basename($file, '.php');
            $config = require $file;
            static::$config[$key] = is_array($config) ? array_merge(static::$config[$key] ?? [], $config) : $config;
        }

        return static::$config;
    }
}
