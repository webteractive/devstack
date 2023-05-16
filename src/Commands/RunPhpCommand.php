<?php

namespace Webteractive\Devstack\Commands;

use Webteractive\Devstack\Process;
use Webteractive\Devstack\WithSignalHandlers;

class RunPHPCommand extends Base
{
    use WithSignalHandlers;

    protected $signature = 'php';
    protected $description = 'Run PHP commands.';

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
            'php',
        ];

        $this->handleTerminationSignals(
            $process = Process::prepare(array_merge($command, $this->fullCommandSignature))
        );

        $process->setTty(true)->run();

        return static::SUCCESS;
    }
}