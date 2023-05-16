<?php

namespace Webteractive\Devstack;

use Symfony\Component\Process\Process as SymfonyProcess;

class Process
{
    public static function prepare(array $command)
    {
        return new SymfonyProcess($command);
    }

    public static function prepareFromShell(string $command)
    {
        return SymfonyProcess::fromShellCommandline($command);
    }
}