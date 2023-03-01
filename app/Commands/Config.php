<?php

namespace App\Commands;

use App\Devstack\WithConfigs;
use LaravelZero\Framework\Commands\Command;

class Config extends Command
{
    use WithConfigs;

    protected $signature = 'config';
    protected $description = 'Set devstack Configurations';

    public function handle()
    {
        $this->configure();
        $this->setConfigurations();
    }
}
