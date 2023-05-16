<?php

namespace Webteractive\Devstack\Commands;

use Webteractive\Devstack\Process;
use Webteractive\Devstack\WithSignalHandlers;

class RunComposerCommands extends Base
{
    use WithSignalHandlers;

    protected $signature = 'composer';
    protected $description = 'Run composer commands.';

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
            'composer',
        ];

        $this->handleTerminationSignals(
            $process = Process::prepare(array_merge($command, $this->fullCommandSignature))
        );

        $process->setTty(true)->run();

        return static::SUCCESS;
    }
}