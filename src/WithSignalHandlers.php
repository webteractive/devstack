<?php

namespace Webteractive\Devstack;

use Symfony\Component\Process\Process;

trait WithSignalHandlers
{
    public function handleSignal(Process $process, int $signal)
    {
        pcntl_signal($signal, function () use ($signal, $process) {
            $process->signal($signal);
        });
    }

    public function handleTerminationSignals(Process $process)
    {
        $this->handleSignal($process, SIGINT);
        // $this->handleSignal($process, SIGSTOP);
        // $this->handleSignal($process, SIGKILL);
    }
}