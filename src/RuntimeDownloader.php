<?php

namespace Webteractive\Devstack;

use ZipArchive;

class RuntimeDownloader
{
    use ShouldConfigure,
        WithHttp;

    protected $command;

    public function __construct($command, $config)
    {
        $this->command = $command;
        $this->config = $config;
    }

    public function download($shouldGetTheLatestRuntimes = true)
    {
        if ($shouldGetTheLatestRuntimes) {
            $this->devstackStorage()->deleteDirectory('runtimes');
            $this->command->info("Downloading fresh runtimes from {$this->config['repository']}.");
        }

        $archive = $this->homePath('runtimes.zip');
        $downloadUrl = join('/', [$this->config['repository'], 'zipball', $this->config['branch']]);
        $response = $this->http()->get($downloadUrl, [
            'headers' => [
                'Authorization' => 'token ' . $this->config['token'],
            ],
            'sink' => $archive
        ]);

        if ($response->getStatusCode() == 200) {
            $zip = new ZipArchive;
            if ($zip->open($archive)) {
                $zip->extractTo($this->homePath('tmp'));
                $zip->close();
                $this->devstackStorage()->delete('runtimes.zip');
                $extracted = $this->devstackStorage()->directories('tmp')[0];
                $extractedName = explode('-', pathinfo($extracted, PATHINFO_BASENAME));
                $hash = array_pop($extractedName);
                $this->command->info("Now using runtimes from the commit {$hash}");
                $runtimes = $this->devstackStorage()->directories($extracted);
                foreach ($runtimes as $runtime) {
                    $runtimeName = pathinfo($runtime, PATHINFO_BASENAME);
                    $this->runtimes[$runtimeName] = $runtime;
                    $this->devstackStorage()->move($runtime, 'runtimes/' . $runtimeName);
                }
                $this->devstackStorage()->deleteDirectory('tmp');
            }
        }
    }
}
