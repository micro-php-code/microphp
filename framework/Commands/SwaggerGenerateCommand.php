<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Commands;

use MicroPHP\Framework\Attribute\Attributes\CMD;
use MicroPHP\Swagger\Swagger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[CMD]
class SwaggerGenerateCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        Swagger::gen();
        $output->writeln('<info>swagger was generated</info>');

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setName('swagger:gen')->setDescription('Generate swagger file');
    }
}
