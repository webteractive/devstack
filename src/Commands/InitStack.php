<?php

namespace Webteractive\Devstack\Commands;

use Webteractive\Devstack\File;
use Webteractive\Devstack\ShouldConfigure;
use Webteractive\Devstack\WithHttp;
use ZipArchive;

class InitStack extends Base
{
    use ShouldConfigure,
        WithHttp;

    protected $signature = 'init
                            {runtime? : The runtime to load}
                            {--ftr : Whether to get the latest runtimes from the runtimes repository}
                            {--dest= : The path where to save the runtimes, defaults to the current working directory.}';
    protected $description = 'Initialize devstack runtime to the current project';

    protected $runtimes = [];

    public function handle(): int
    {
        $this->ensureConfigurationIsSet(
            $runtime = $this->argument('runtime')
        );

        $this->resolveRuntimes();

        if (is_null($runtime)) {
            $runtime = $this->choice(
                'Please select runtime to load:',
                $this->getAvailableRuntimes()
            );
        }

        $destination = $this->option('dest', getcwd());

        if ($resolvedRuntime = ($this->runtimes[$runtime] ?? null)) {
            if ((new File)->copyDirectory($this->devstackStorage()->path($resolvedRuntime), $destination)) {
                $this->info("
Runtime for <comment>{$resolvedRuntime}</comment> is now loaded to {$destination}.

You can now run the <comment>dev</comment> command. To get started, run <comment>dev up</comment> or <comment>dev up -d</comment> to start the docker containers.
For the first run, it will build the images first and proceed running the containers. To stop the
containers, run <comment>dev down</comment>. For more details on the avaialble commands, run <comment>dev help</comment>.

To exclude the runtime files to your repository, add the following files below to your .gitignore file:

<fg=white>./docker</>
<fg=white>dev</>
<fg=white>docker-compose.yml</>

You're now all set, happy trails!
                ");
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
        $shouldGetTheLatestRuntimes = $this->option('ftr', false);

        if ($shouldGetTheLatestRuntimes || $runtimesDoesntExistsYet) {
            if ($runtimesDoesntExistsYet) {
                $this->warn("Unable to locate runtimes locally, downloading runtimes from {$this->config['repository']}.");
            }

            if ($shouldGetTheLatestRuntimes) {
                $this->info("Downloading fresh runtimes from {$this->config['repository']}.");
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
                    $runtimes = $this->devstackStorage()->directories($this->devstackStorage()->directories('tmp')[0]);
                    foreach ($runtimes as $runtime) {
                        $runtimeName = pathinfo($runtime, PATHINFO_BASENAME);
                        $this->runtimes[$runtimeName] = $runtime;
                        $this->devstackStorage()->move($runtime, 'runtimes/' . $runtimeName);
                    }
                    $this->devstackStorage()->deleteDirectory('tmp');
                }
            }
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
}