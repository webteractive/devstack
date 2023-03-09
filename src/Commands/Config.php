<?php

namespace Webteractive\Devstack\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webteractive\Devstack\ShouldConfigure;
use Webteractive\Devstack\WithStorage;

class Config extends Base
{
    use ShouldConfigure;

    protected $signature = 'config';
    protected $description = 'Configure Devstack runtime settings';

    public function handle(): int
    {
        $this->storeConfiguration();
        return static::SUCCESS;
    }
}