<?php

namespace Webteractive\Devstack\Commands;

use Webteractive\Devstack\Process;
use Symfony\Component\Process\Process as SymfonyProcess;
use Webteractive\Devstack\WithSignalHandlers;

class DockerCompose extends Base
{
    use WithSignalHandlers;

    public function __construct($name, $description)
    {
        $this->signature = $name;
        $this->description = $description;
        
        $this->ignoreValidationErrors();

        parent::__construct();
    }

    public function handle(): int
    {
        $commandSignature = array_merge(
            ['docker', 'compose', $this->getName()],
            $this->fullCommandSignature
        );

        $this->handleTerminationSignals(
            $process = Process::prepare($commandSignature)
        );

        $process->setTimeout(null)
            ->setTty(true)
            ->run();

        return static::SUCCESS;
    }
}
