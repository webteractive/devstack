<?php

namespace Webteractive\Devstack;

use Symfony\Component\Console\Application;
use Webteractive\Devstack\Commands\RunDockerCommands;

class RegisterDockerComposeCommands
{
    protected static $commands = [
        'build' => 'Build or rebuild services.',
        'config' => 'Parse, resolve and render compose file in canonical format.',
        'cp' => 'Copy files/folders between a service container and the local filesystem.',
        'create' => 'Creates containers for a service.',
        'down' => 'Stop and remove containers, networks.',
        'events' => 'Receive real time events from containers.',
        'exec' => 'Execute a command in a running container.',
        'images' => 'List images used by the created containers.',
        'kill' => 'Force stop service containers.',
        'logs' => 'View output from containers.',
        'ls' => 'List running compose projects.',
        'pause' => 'Pause services.',
        'port' => 'Print the public port for a port binding.',
        'ps' => 'List containers.',
        'pull' => 'Pull service images.',
        'push' => 'Push service images',
        'restart' => 'Restart service containers.',
        'rm' => 'Removes stopped service containers.',
        'run' => 'Run a one-off command on a service.',
        'start' => 'Start services.',
        'stop' => 'Stop services.',
        'top' => 'Display the running processes.',
        'unpause' => 'Unpause services.',
        'up' => 'Create and start containers.',
        'version' => 'Show the Docker Compose version information.',
    ];

    public static function register(Application $app, $prefix = 'runtime')
    {
        foreach (static::$commands as $name => $description) {
            $app->add(new RunDockerCommands($name, $description));
        }
    }
}