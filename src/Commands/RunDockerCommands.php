<?php

namespace Webteractive\Devstack\Commands;

use Symfony\Component\Process\Process;

class RunDockerCommands extends Base
{
    public function __construct($name, $description)
    {
        $this->signature = $name;
        $this->description = $description;
        
        $this->ignoreValidationErrors();

        parent::__construct();
    }

    public function handle(): int
    {
        [, $name] = explode(':', $this->getName());
        $processSignature = ['docker', 'compose', $name];
        $process = new Process(array_merge($processSignature, $this->fullCommandSignature));
        $process->setTty(true);
        $process->run();
        $process->run(function ($type, $buffer) {
            $this->line($buffer);
        });

        return static::SUCCESS;
    }
}
