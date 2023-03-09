<?php

namespace Webteractive\Devstack;

use GuzzleHttp\Client;

trait WithHttp
{
    public function http()
    {
        return new Client();
    }
}