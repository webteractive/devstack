<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;

class InitStack extends Command
{
    protected $signature = 'init';

    protected $description = 'Initialize devstack to the current project';

    public function handle()
    {
        $this->info('Cool');
    }
}
