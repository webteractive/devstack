<?php

namespace Webteractive\Devstack\Commands;

use Webteractive\Devstack\Process;
use Webteractive\Devstack\WithSignalHandlers;

class RunLaravelArtisanCommand extends Base
{
    use WithSignalHandlers;

    protected $signature = 'artisan';
    protected $description = 'Run Laravel\'s artisan command.';

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
            'artisan',
        ];

        $this->handleTerminationSignals(
            $process = Process::prepare(array_merge($command, $this->fullCommandSignature))
        );

        $process->setTty(true)->run();

        return static::SUCCESS;
    }
}