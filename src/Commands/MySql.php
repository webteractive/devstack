<?php

namespace Webteractive\Devstack\Commands;

use Webteractive\Devstack\Process;
use Webteractive\Devstack\WithSignalHandlers;

class MySql extends Base
{
    use WithSignalHandlers;

    protected $signature = 'mysql
                            {--p|password=password : The password to use.}
                            {--u|user=user : The user to use.}
                            {--db|database= : The database to use.}';
    protected $description = 'Start a MySQL CLI session within the <comment>mysql</comment> container.';

    public function handle(): int
    {
        $password = $this->output->

        $this->handleTerminationSignals(
            $process = Process::prepareFromShell('docker compose exec -it mysql bash -c "MYSQL_PWD=password mysql -u user;"')
        );

        $process->setTty(true)->run();

        return static::SUCCESS;
    }
}