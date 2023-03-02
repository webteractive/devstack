<?php

namespace App\Commands;

use ZipArchive;
use App\Devstack\WithConfigs;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use LaravelZero\Framework\Commands\Command;

class InitStack extends Command
{
    use WithConfigs;

    protected $signature = 'init
                            {runtime : The runtime to load}
                            {--latest-runtimes : Whether to get the latest runtimes from the runtimes repository}';

    protected $description = 'Initialize devstack runtime to the current project';

    protected $runtimes = [];

    public function handle()
    {
        $this->configure(
            $runtime = $this->argument('runtime')
        );
        $this->resolveRuntimes();

        $cwd = getcwd() . '/.example';

        if ($this->runtimes[$runtime] ?? null) {
            if (File::copyDirectory($this->disk->path($this->runtimes[$runtime]), $cwd)) {
                $this->info("Runtime for <comment>{$runtime}</comment> is now loaded to {$cwd}.");
                $this->info("
You can now run the <comment>dev</comment> command. To get started, run <comment>dev up</comment> or <comment>dev up -d</comment> to start the docker containers.
For the first run, it will build the images first and proceed running the containers. To stop the
containers, run <comment>dev down</comment>. For more details on the avaialble commands, run <comment>dev help</comment>.

To exclude the runtime files to your repository, add the following files below to your .gitignore file:
<fg=white>./docker</>
<fg=white>dev</>
<fg=white>docker-compose.yml</>

You're now all set, happy trails!
                ");
                $this->notify("Hello Web Artisan", "Love beautiful..", "icon.png");
            } else {
                $this->error("Failed to load the {$runtime} runtime to {$cwd}, copy of runtime was unsuccessful.");
            }
        } else {
            $this->error("Failed to load the {$runtime} runtime, the requested runtime is unsupported.");
        }
    }

    public function resolveRuntimes()
    {
        $runtimesDoesntExistsYet = !$this->disk->exists('runtimes');
        $shouldGetTheLatestRuntimes = $this->option('latest-runtimes');

        if ($shouldGetTheLatestRuntimes || $runtimesDoesntExistsYet) {
            if ($runtimesDoesntExistsYet) {
                $this->warn("Unable to locate runtimes locally, downloading runtimes from {$this->config['repository']}.");
            }

            if ($shouldGetTheLatestRuntimes) {
                $this->info("Downloading fresh runtimes from {$this->config['repository']}.");
            }
            
            $archive = $this->homePath . '/runtimes.zip';
            $downloadUrl = join('/', [$this->config['repository'], 'zipball', $this->config['branch']]);
            $response = Http::withToken($this->config['token'], 'token')
                ->withOptions(['sink' => $archive])
                ->get($downloadUrl);

            if ($response->successful()) {
                $zip = new ZipArchive;
                if ($zip->open($archive)) {
                    $zip->extractTo($this->homePath . '/tmp');
                    $zip->close();
                    $this->disk->delete('runtimes.zip');
                    $runtimes = $this->disk->directories($this->disk->directories('tmp')[0]);
                    foreach ($runtimes as $runtime) {
                        $runtimeName = pathinfo($runtime, PATHINFO_BASENAME);
                        $this->runtimes[$runtimeName] = $runtime;
                        $this->disk->move($runtime, 'runtimes/' . $runtimeName);
                    }
                    $this->disk->deleteDirectory('tmp');
                }
            }
        }

        foreach ($this->disk->directories('runtimes') as $runtimePath) {
            $this->runtimes[pathinfo($runtimePath, PATHINFO_BASENAME)] = $runtimePath;
        }
    }
}
