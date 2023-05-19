<?php

namespace Webteractive\Devstack\Commands;

use Webteractive\Devstack\ShouldConfigure;

class MakeRuntime extends Base
{
    use ShouldConfigure;

    protected $signature = 'make';
    protected $description = 'Create a runtime in your current working directory.';

    public function handle(): int
    {

        return static::SUCCESS;
    }
}