<?php

namespace Webteractive\Devstack\Commands;

use Webteractive\Devstack\Process;
use Webteractive\Devstack\WithSignalHandlers;

class Wp extends Base
{
    use WithSignalHandlers;

    protected $signature = 'wp';
    protected $description = 'Run <info>WordPress CLI</info> commands within the <info>app</info> container.';

    public function shouldIgnoreValidationErrors(): bool
    {
        return true;
    }

    public function handle(): int
    {
        $command = [
            'docker',
            'compose',
            'exec',
            '-u',
            'dev',
            '-T',
            'app',
            'wp',
        ];

        $this->handleTerminationSignals(
            $process = Process::prepare(array_merge($command, $this->fullCommandSignature))
        );

        $process->setTimeout(null)
            ->setTty(true)
            ->run();

        return static::SUCCESS;
    }
}