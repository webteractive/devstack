<?php

namespace App\Commands;

use ZipArchive;
use App\Devstack\WithConfigs;
use Illuminate\Support\Facades\Http;
use LaravelZero\Framework\Commands\Command;

class InitStack extends Command
{
    use WithConfigs;

    protected $signature = 'init {runtime} {--no-';

    protected $description = 'Initialize devstack to the current project';

    protected $runtimes = [];

    public function handle()
    {
        $this->configure(
            $runtime = $this->argument('runtime')
        );
        $this->resolveRuntimes();

        $cwd = getcwd() . '/.example';
        

        if ($this->runtimes[$runtime] ?? null) {

            $result = $this->disk->copy($this->runtimes[$runtime], $cwd);

            dd($result, $this->runtimes, $cwd, $this->runtimes[$runtime]);
        }

        $this->info('Cool');
    }

    public function resolveRuntimes()
    {
        if (!$this->disk->exists('runtimes')) {
            $this->warn("Unable to locate runtimes locally, downloading from source using {$this->config['repository']}.");
            
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
