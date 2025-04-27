<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Commands;

use InvalidArgumentException;
use MicroPHP\Framework\Attribute\Attributes\CMD;
use MicroPHP\Framework\Config\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[CMD]
class PublishConfigCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $config = Config::get('publish.' . $name);
        if (empty($config)) {
            throw new InvalidArgumentException('config is not found: ' . $name);
        }
        copy($config['from'], $config['to']);
        $output->writeln('<info>Config is published at: ' . realpath($config['to']) . '</info>');

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setName('config:publish')->setDescription('Publish the package config')
            ->addArgument('name', InputArgument::REQUIRED, 'package config name');
    }
}
