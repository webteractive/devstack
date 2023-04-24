<?php

namespace Webteractive\Devstack;


trait ShouldConfigure
{
    use WithStorage;

    protected $repository;
    protected $branch;
    protected $token;
    protected $config;
    protected $runtimes = [];

    public function ensureConfigurationIsSet($runtime = null)
    {
        if ($this->devstackStorage()->doesntExists('config.json')) {
            if ($runtime) {
                $this->info("\nBefore we can start installing the <comment>{$runtime}</comment> runtime, we have
to configure the credentials needed to download
it. Please follow the next screens.
                ");
            }

            $this->storeConfiguration();
        }

        $this->config = json_decode($this->devstackStorage()->get('config.json'), true);

        return $this;
    }

    public function storeConfiguration()
    {
        $this->repository = $this->ask('Please supply the devstack runtimes repository:', null);
        $this->branch = $this->ask('Please supply the devstack runtimes repository branch:', 'main');
        $this->token = $this->ask('Please supply the personal access token of the devstack runtime repository:', null);
        
        $this->devstackStorage()->put('config.json', json_encode([
            'repository' => $this->repository,
            'branch' => $this->branch,
            'token' => $this->token,
        ], JSON_PRETTY_PRINT));

        $this->line()
            ->info("Configuration are set!")
            ->line($this->devstackStorage()->get('config.json'));
    }
}