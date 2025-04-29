<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Config;

use MicroPHP\Framework\Utils\FileScanner;
use ReflectionException;
use Symfony\Component\Finder\Finder;

class ConfigProviderScanner
{
    use FileScanner;

    /**
     * @throws ReflectionException
     */
    public function scan(): array
    {
        $finder = new Finder();
        $finder->in(base_path('/vendor/microphp/*/src'))
            ->files()
            ->depth('==0')
            ->name('ConfigProvider.php');
        $configs = [];
        foreach ($finder as $file) {
            $reflectionClass = $this->getReflectClassFromFile($file);
            $instance = $reflectionClass->newInstance();
            if ($instance instanceof ConfigProviderInterface) {
                $configs = array_merge_recursive($configs, $instance->config());
            }
        }

        return $configs;
    }
}
