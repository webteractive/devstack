<?php

namespace Webteractive\Devstack\Commands;

use Webteractive\Devstack\RuntimeDownloader;
use Webteractive\Devstack\File;
use Webteractive\Devstack\PublicRuntimeDownloader;
use Webteractive\Devstack\ShouldConfigure;

class InitStack extends Base
{
    use ShouldConfigure;

    protected $signature = 'init
                            {runtime? : The runtime to load}
                            {--flr : Whether to fetch the latest runtimes from the runtimes repository}
                            {--dest= : The path where to save the runtimes, defaults to the current working directory.}';
    protected $description = 'Initialize devstack runtime to the current project';

    public function handle(): int
    {
        $destination = $this->option('dest', getcwd());
        
        $this->ensureConfigurationIsSet(
            $runtime = $this->argument('runtime')
        );

        if (filter_var($runtime, FILTER_VALIDATE_URL)) {
            $resolvedRuntime = (new PublicRuntimeDownloader)->download($runtime);
            if ((new File)->copyDirectory($this->devstackStorage()->path($resolvedRuntime), $destination)) {
                $this->info($this->makeMessage($runtime, $destination));
            } else {
                $this->error("Failed to load the {$runtime} runtime to {$destination}, copy of runtime was unsuccessful.");
            }

            return static::SUCCESS;
        }

        $this->resolveRuntimes();

        if (is_null($runtime)) {
            $runtime = $this->choice(
                'Please select runtime to load:',
                $this->getAvailableRuntimes()
            );
        }

        

        if ($resolvedRuntime = ($this->runtimes[$runtime] ?? null)) {
            if ((new File)->copyDirectory($this->devstackStorage()->path($resolvedRuntime), $destination)) {
                $this->info($this->makeMessage($runtime, $destination));
            } else {
                $this->error("Failed to load the {$runtime} runtime to {$destination}, copy of runtime was unsuccessful.");
            }
        } else {
            $available = collect($this->getAvailableRuntimes())->join(', ', ' and ');
            $this->error("

 Failed to load the {$runtime} runtime, the requested runtime not supported.
 Only {$available} are supported.
            ");
        }

        return static::SUCCESS;
    }

    private function resolveRuntimes()
    {
        $runtimesDoesntExistsYet = $this->devstackStorage()->doesntExists('runtimes');
        $shouldGetTheLatestRuntimes = $this->option('flr', false);

        if ($shouldGetTheLatestRuntimes || $runtimesDoesntExistsYet) {
            if ($runtimesDoesntExistsYet) {
                $this->warn("Unable to locate runtimes locally, downloading runtimes from {$this->config['repository']}.");
            }

            (new RuntimeDownloader($this, $this->config))->download($shouldGetTheLatestRuntimes);
        }

        foreach ($this->devstackStorage()->directories('runtimes') as $runtimePath) {
            $this->runtimes[pathinfo($runtimePath, PATHINFO_BASENAME)] = $runtimePath;
        }
    }

    public function getAvailableRuntimes()
    {
        return collect($this->devstackStorage()->directories('runtimes'))
            ->map(fn ($item) => pathinfo($item, PATHINFO_BASENAME));
    }

    public function makeMessage($runtime, $destination)
    {
        return "
Runtime for <comment>{$runtime}</comment> is now loaded to {$destination}.

You can now run the <comment>dev</comment> command. To get started, run <comment>dev up</comment> or <comment>dev up -d</comment> to start the docker containers.
For the first run, it will build the images first and proceed running the containers. To stop the
containers, run <comment>dev down</comment>. For more details on the avaialble commands, run <comment>dev help</comment>.

To exclude the runtime files to your repository, add the following files below to your .gitignore file:

<fg=white>/docker</>
<fg=white>dev</>
<fg=white>docker-compose.yml</>

You're now all set, happy trails!";
    }
}
