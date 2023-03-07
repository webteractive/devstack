<?php

namespace Webteractive\Devstack;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'init',
    description: 'Initialize Devstack runtime in the current directory.',
    hidden: false,
)]
class InitStack extends Command
{
    public function configure(): void
    {
        $this->addArgument('runtime', InputArgument::OPTIONAL, 'The runtime to initialize');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Cool');
        return Command::SUCCESS;
    }
}