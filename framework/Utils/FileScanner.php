<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Utils;

use ReflectionClass;
use ReflectionException;
use SplFileInfo;

trait FileScanner
{
    /**
     * @throws ReflectionException
     */
    protected function getReflectClassFromFile(SplFileInfo $file): ?ReflectionClass
    {
        $content = file_get_contents($file->getRealPath());

        $namespace = '';

        $tokens = token_get_all($content);

        for ($i = 0; $i < count($tokens); ++$i) {
            // 读取命名空间
            if ($tokens[$i][0] === T_NAMESPACE) {
                $namespace = $this->getNamespace($tokens, $i);
            }

            // 读取类名
            if ($tokens[$i][0] === T_CLASS && ! empty(trim($tokens[$i + 2][1] ?? ''))) {
                return $this->getReflectionClass($tokens[$i + 2][1], $namespace);
            }
        }

        return null;
    }

    /**
     * 获取命名空间
     */
    private function getNamespace(array $tokens, int $namespaceIndex): string
    {
        $namespace = '';
        for ($i = $namespaceIndex + 1; $i < count($tokens); ++$i) {
            if ($tokens[$i] === ';') {
                break;
            }
            $namespace .= $tokens[$i][1];
        }

        return trim($namespace);
    }

    /**
     * 创建反射类对象
     *
     * @throws ReflectionException
     */
    private function getReflectionClass(string $className, string $namespace): ReflectionClass
    {
        $fullName = trim($namespace, '\\') . '\\' . $className;

        return new ReflectionClass($fullName);
    }
}
