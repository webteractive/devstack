<?php

namespace Webteractive\Devstack\Commands;

use Webteractive\Devstack\ShouldConfigure;
use Webteractive\Devstack\RuntimeDownloader;

class DownloadRuntimes extends Base
{
    use ShouldConfigure;

    protected $signature = 'download';
    protected $description = 'Download latest runtimes';

    public function handle(): int
    {
        $this->ensureConfigurationIsSet();

        (new RuntimeDownloader($this, $this->config))->download();

        return static::SUCCESS;
    }
}