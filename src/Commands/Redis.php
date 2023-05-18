<?php

namespace Webteractive\Devstack\Commands;

use Webteractive\Devstack\Process;
use Webteractive\Devstack\WithSignalHandlers;

class Redis extends Base
{
    use WithSignalHandlers;

    protected $signature = 'redis';
    protected $description = 'Start a Redis CLI session within the <comment>redis</comment> container.';

    public function handle(): int
    {

        $this->handleTerminationSignals(
            $process = Process::prepareFromShell('docker compose exec -it redis redis-cli')
        );

        $process->setTty(true)
            ->setTimeout(60 * 60 * 2)
            ->setIdleTimeout(60 * 60 * 8)
            ->run();

        return static::SUCCESS;
    }
}