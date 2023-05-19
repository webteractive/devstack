<?php

namespace Webteractive\Devstack\Commands;

use Webteractive\Devstack\Process;
use Webteractive\Devstack\WithSignalHandlers;

class Php extends Base
{
    use WithSignalHandlers;

    protected $signature = 'php';
    protected $description = 'Run <info>PHP</info> CLI within the <info>app</info> container.';

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

        $process->setTimeout(null)
            ->setTty(true)
            ->run();

        return static::SUCCESS;
    }
}