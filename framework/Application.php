<?php

declare(strict_types=1);

namespace MicroPHP\Framework;

use League\Route\Strategy\ApplicationStrategy;
use MicroPHP\Framework\Attribute\Scanner\AttributeScanner;
use MicroPHP\Framework\Attribute\Scanner\AttributeScannerMap;
use MicroPHP\Framework\Config\Config;
use MicroPHP\Framework\Container\Container;
use MicroPHP\Framework\Container\ContainerInterface;
use MicroPHP\Framework\Http\ServerFactory;
use MicroPHP\Framework\Router\Router;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

final class Application
{
    private static ContainerInterface $container;

    private function __construct() {}

    /**
     * @throws Throwable
     */
    public static function boot(): Application
    {
        $app = new Application();
        $app->init();
        self::getContainer()->add(Application::class, $app);

        return $app;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function run(OutputInterface $output): Application
    {
        $app = new Application();
        $app->init();
        $app->listen($output);

        return $app;
    }

    public static function getContainer(): ContainerInterface
    {
        return Application::$container;
    }

    /**
     * @template T
     *
     * @param  class-string<T> $class
     * @return T
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function getClass(string $class)
    {
        return Application::getContainer()->get($class);
    }

    /**
     * @throws ReflectionException
     */
    private function init(): void
    {
        Env::load();
        $this->initContainer();
        $config = $this->getConfig();
        $this->scanAttributes($config['app']['scanner']);
        $router = $this->getRouter($config['routes']);
        $router->middlewares($config['middlewares']);
        Application::getContainer()->add(Router::class, $router);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function listen(OutputInterface $output): void
    {
        $router = Application::getContainer()->get(Router::class);
        ServerFactory::newServer()->run($router, $output);
    }

    /**
     * @throws ReflectionException
     */
    private function getConfig(): array
    {
        return Config::load(BASE_PATH . '/config');
    }

    private function getRouter(Router $router): Router
    {
        $strategy = new ApplicationStrategy();
        $strategy->setContainer(Application::getContainer());
        $router->setStrategy($strategy);

        return $router;
    }

    /**
     * @throws ReflectionException
     */
    private function scanAttributes(array $config): void
    {
        $result = (new AttributeScanner())->scan($config['directories']);
        Application::$container->add(AttributeScannerMap::class, $result);
    }

    private function initContainer(): void
    {
        Application::$container = new Container();
        Application::$container->defaultToShared();
    }
}
