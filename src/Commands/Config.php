<?php

namespace Webteractive\Devstack\Commands;

use Webteractive\Devstack\ShouldConfigure;

class Config extends Base
{
    use ShouldConfigure;

    protected $signature = 'config';
    protected $description = 'Configure private devstack runtime settings.';

    public function handle(): int
    {
        $this->storeConfiguration();
        return static::SUCCESS;
    }
}