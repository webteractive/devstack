<?php

namespace Webteractive\Devstack\Commands;

use Webteractive\Devstack\Process;
use Webteractive\Devstack\WithSignalHandlers;

class Shell extends Base
{
    use WithSignalHandlers;

    protected $signature = 'shell {--r|root : As a root user.}';
    protected $description = 'Start a shell session within the application container';

    public function handle(): int
    {
        $command = [
            'docker',
            'compose',
            'exec',
        ];

        if (!$this->input->getOption('root')) {
            $command[] = '-u';
            $command[] = 'dev';
        }

        $this->handleTerminationSignals(
            $process = Process::prepare(array_merge($command, ['app', 'bash']))
        );

        $process->setTty(true)
            ->setTimeout(60 * 60 * 2)
            ->setIdleTimeout(60 * 60 * 8)
            ->run();

        return static::SUCCESS;
    }
}