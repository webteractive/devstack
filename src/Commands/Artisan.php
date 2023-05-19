<?php

namespace Webteractive\Devstack\Commands;

use Webteractive\Devstack\Process;
use Webteractive\Devstack\WithSignalHandlers;

class Artisan extends Base
{
    use WithSignalHandlers;

    protected $signature = 'artisan';
    protected $description = 'Run <info>Laravel\'s artisan</info> command within the <info>app</info> container.';

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

        $process->setTimeout(null)
            ->setTty(true)
            ->run();

        return static::SUCCESS;
    }
}