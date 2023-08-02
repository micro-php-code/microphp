<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetRoadRunnerCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $command = "curl --proto '=https' --tlsv1.2 -sSf  https://raw.githubusercontent.com/roadrunner-server/roadrunner/master/download-latest.sh | sh && tar -xzvf roadrunner-*.tar.gz && rm -rf roadrunner-*.tar.gz && mv roadrunner-*/rr . && rm -rf roadrunner-* && chmod +x ./rr";
        $result = shell_exec($command);
        $output->writeln($result);

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setName('roadrunner:get')
            ->setDescription('get last version of roadrunner');
    }
}