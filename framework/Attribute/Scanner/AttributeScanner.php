<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Attribute\Scanner;

use MicroPHP\Framework\Utils\FileScanner;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Finder\Finder;

class AttributeScanner
{
    use FileScanner;

    private AttributeScannerMap $map;

    public function __construct()
    {
        $this->map = new AttributeScannerMap();
    }

    /**
     * @throws ReflectionException
     */
    public function scan(array $directories): AttributeScannerMap
    {
        $directories = array_map(function ($directory) {
            return realpath(base_path($directory));
        }, $directories);
        $finder = new Finder();
        $finder->in($directories)
            ->files()
            ->name('*.php');
        foreach ($finder as $file) {
            $reflectionClass = $this->getReflectClassFromFile($file);
            if ($reflectionClass) {
                $this->getAttributes($reflectionClass);
            }
        }

        return $this->map;
    }

    private function getAttributes(ReflectionClass $class): void
    {
        $attributes = $class->getAttributes();
        foreach ($attributes as $attribute) {
            $this->map->add($class->getName(), $attribute->getName(), $attribute->getArguments());
        }
    }
}
