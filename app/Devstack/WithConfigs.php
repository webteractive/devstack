<?php

namespace App\Devstack;

use Illuminate\Support\Facades\Storage;

trait WithConfigs
{
    protected $disk;
    protected $homePath;
    protected $config;

    public function configure($runtime = null)
    {
        $this->homePath = posix_getpwuid(fileowner(__FILE__))['dir'] . '/.config/devstack';
        $this->disk = Storage::build([
            'driver' => 'local',
            'root' => $this->homePath,
        ]);

        if (!$this->disk->exists('config.json')) {
            if ($runtime) {
                $this->info("\nBefore we can start installing the <comment>{$runtime}</comment> runtime, we have
to configure the credentials needed to download
it. Please follow the next screens.
                ");


                $this->setConfigurations();
            }
        }

        $this->config = json_decode($this->disk->get('config.json'), true);
    }

    public function setConfigurations()
    {
        $repository = $this->ask('Please supply the devstack runtimes repository.');
        $branch = $this->ask('Please supply the devstack runtimes repository branch.', 'main');
        $token = $this->ask('Please supply the personal access token of the devstack runtime repository.');

        $this->disk->put('config.json', json_encode([
            'repository' => $repository,
            'branch' => $branch,
            'token' => $token,
        ], JSON_PRETTY_PRINT));

        $this->info("Configuration set!");
        $this->line( '<comment>' . $this->disk->get('config.json') . '</comment>');
    }
}
