<?php

namespace Webteractive\Devstack;

use ZipArchive;
use Illuminate\Support\Str;

class PublicRuntimeDownloader
{
    use WithStorage,
        WithHttp;

    public function download($url)
    {
        $archive = $this->homePath('runtimes.zip');
        $finalDownloadUrl = $this->resolveDownloadUrl($url);
        $response = $this->http()->get($finalDownloadUrl, [
            'sink' => $archive
        ]);

        if ($response->getStatusCode() == 200) {
            if (($zip = new ZipArchive)->open($archive)) {
                $zip->extractTo($this->homePath('tmp'));
                $zip->close();
                $this->devstackStorage()->delete('runtimes.zip');
                $extracted = $this->devstackStorage()->directories('tmp')[0];
                $newRuntimeName = sha1($finalDownloadUrl);
                if ($this->devstackStorage()->exists('runtimes/' . $newRuntimeName)) {
                   $this->devstackStorage()->deleteDirectory('runtimes/' . $newRuntimeName);
                }
                $this->devstackStorage()->move($extracted, 'runtimes/' . $newRuntimeName);
                $this->devstackStorage()->deleteDirectory('tmp');
                return join('/', ['runtimes', $newRuntimeName]);
            }
        }

        return false;
    }

    public function isFromGithub($url)
    {
        return Str::contains($url, 'github.com');
    }

    public function resolveDownloadUrl($url)
    {
        if ($this->isFromGithub($url)) {
            $downloadUrl = Str::of($url)
                ->replace('https://github.com', 'https://api.github.com/repos')
                ->append('/releases/latest')
                ->toString();
            $response = $this->http()->get($downloadUrl);
            return json_decode($response->getBody(), true)['zipball_url'];
        }

        return $url;
    }
}