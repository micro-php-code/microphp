<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Commands;

use MicroPHP\Framework\Application;
use MicroPHP\Framework\Attribute\Attributes\CMD;
use MicroPHP\Framework\Http\Enum\Driver;
use MicroPHP\Framework\Http\ServerConfig;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

#[CMD]
class StartCommand extends Command
{
    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $serverConfig = new ServerConfig();

        return match ($serverConfig->getDriver()) {
            Driver::WORKERMAN => $this->workermanStart(),
            Driver::ROADRUNNER => $this->roadrunnerStart($output, $serverConfig),
            Driver::SWOOLE => $this->swooleStart(),
            Driver::AMP => $this->ampStart(),
            default => throw new RuntimeException('unsupported driver: ' . $serverConfig->getDriver()),
        };
    }

    protected function configure(): void
    {
        $this->setName('start')->setDescription('Start the application');
    }

    private function roadrunnerStart(OutputInterface $output, ServerConfig $serverConfig): int
    {
        $process = new Process(
            [
                './rr',
                'serve',
                '-o http.pool.num_workers=' . $serverConfig->getWorkers(),
                '-o http.address=' . $serverConfig->getUri(),
            ],
            timeout: null
        );
        $process->mustRun(function ($type, $buffer) use ($output) {
            if (str_contains($buffer, 'ERROR')) {
                $output->writeln('<error>' . $buffer . '</error>');
            } elseif (str_contains($buffer, 'WARN')) {
                $output->writeln('<comment>' . $buffer . '</comment>');
            } elseif (preg_match('/INFO\s+server/', $buffer)) {
                $output->writeln('<fg=cyan>' . $buffer . '</>');
            } else {
                $output->writeln('<info>' . $buffer . '</info>');
            }
        });

        return Command::SUCCESS;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    private function workermanStart(): int
    {
        Application::getClass(Application::class)->run();

        return Command::SUCCESS;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    private function swooleStart(): int
    {
        Application::getClass(Application::class)->run();

        return Command::SUCCESS;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     * @throws NotFoundExceptionInterface
     */
    private function ampStart(): int
    {
        Application::getClass(Application::class)->run();

        return Command::SUCCESS;
    }
}
