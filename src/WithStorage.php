<?php

namespace Webteractive\Devstack;


trait WithStorage
{
    public function homePath(string $path = null)
    {
        $paths = [
            posix_getpwnam(get_current_user())['dir'],
            '.devstack'
        ];

        if ($path) {
            array_push($paths, $path);
        }

        return join('/', $paths);
    }
    private function storage($path)
    {
        return Fs::make($path);
    }

    public function devstackStorage()
    {
        return $this->storage($this->homePath());
    }
}