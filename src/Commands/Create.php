<?php

namespace Webteractive\Devstack\Commands;

use Webteractive\Devstack\ShouldConfigure;

class Create extends Base
{
    use ShouldConfigure;

    protected $signature = 'create';
    protected $description = 'Create a runtime in your current working directory';

    public function handle(): int
    {
        $this->storeConfiguration();
        return static::SUCCESS;
    }
}