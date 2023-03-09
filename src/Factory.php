<?php

namespace Webteractive\Devstack;

use Symfony\Component\Console\Command\Command as BaseCommand;

class Factory
{
    public static function make($handler): BaseCommand
    {
        $handler = new $handler;


        return new BaseCommand;
    }
}