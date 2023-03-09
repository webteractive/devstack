<?php

namespace Webteractive\Devstack;

use League\Flysystem\DirectoryListing;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;

class Fs
{
    protected $filesystem;
    protected $basePath;

    public function __construct(string $location)
    {
        $this->filesystem = new Filesystem(
            new LocalFilesystemAdapter($this->basePath = $location)
        );
    }

    public static function make($location)
    {
        return new static($location);
    }

    public function exists($path)
    {
        return $this->filesystem->fileExists($path)
            || $this->filesystem->directoryExists($path);
    }

    public function doesntExists($path)
    {
        return !$this->exists($path);
    }

    public function mkdir(string $location, array $config = [])
    {
        $this->filesystem->createDirectory($location, $config);
    }

    public function put($path, $contents, $config = [])
    {
        $this->filesystem->write($path, $contents, $config);
    }

    public function delete($path)
    {
        $this->filesystem->delete($path);
    }

    public function deleteDirectory($path)
    {
        $this->filesystem->deleteDirectory($path);
    }

    public function get($path)
    {
        return $this->filesystem->read($path);
    }

    public function path($location)
    {
        return join('/', [$this->basePath, $location]);
    }

    public function move($source, $destination, $config = [])
    {
        $this->filesystem->move($source, $destination, $config);
    }

    public function copy($source, $destination, $config = [])
    {
        $this->filesystem->copy($source, $destination, $config);
    }

    public function directories($path)
    {
        return $this->filesystem
            ->listContents($path, false)
            ->filter(fn ($item) => $item->isDir())
            ->map(fn ($item) => $item->path())
            ->toArray();
    }

    public function filesystem()
    {
        return $this->filesystem;
    }
}
