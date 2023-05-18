<?php

namespace Webteractive\Devstack;

use Dotenv\Dotenv;
use Illuminate\Support\Arr;

class Env
{
    protected $dotenv;

    public function __construct($path)
    {   
        $this->dotenv = Dotenv::createImmutable($path)->safeLoad();
    }

    public function get($key, $default = null)
    {
        return Arr::get($this->dotenv, $key, $default);
    }
}
