<?php

namespace Webteractive\Devstack\Commands;

use Webteractive\Devstack\Process;
use Webteractive\Devstack\WithSignalHandlers;

class Redis extends Base
{
    use WithSignalHandlers;

    protected $signature = 'redis';
    protected $description = 'Start a <info>Redis CLI</info> session within the <info>redis</info> container.';

    public function handle(): int
    {

        $this->handleTerminationSignals(
            $process = Process::prepareFromShell('docker compose exec -it redis redis-cli')
        );

        $process->setTty(true)
            ->setTimeout(null)
            ->run();

        return static::SUCCESS;
    }
}