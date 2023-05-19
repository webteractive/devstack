<?php

namespace Webteractive\Devstack\Commands;

use Webteractive\Devstack\Env;
use Webteractive\Devstack\Process;
use Webteractive\Devstack\WithSignalHandlers;

class MySql extends Base
{
    use WithSignalHandlers;

    protected $signature = 'mysql
                            {--p|password= : The password to use.}
                            {--u|user= : The user to use.}
                            {--db|database= : The database to use.}
                            {--env= : The path to the .env file. Default\'s to the current working directory.}';
    protected $description = 'Start a <info>MySQL CLI</info> session within the <info>mysql</info> container.';

    public function handle(): int
    {
        $defaults = [
            'password' => 'password',
            'user' => 'user',
            'database' => null,
        ];
        $envPath = $this->input->getOption('env') ?? getcwd();
        if (file_exists($envPath . '/.env')) {
            $env = new Env($envPath);
            $defaults['password'] = $env->get('DB_PASSWORD');
            $defaults['user'] = $env->get('DB_USERNAME');
            $defaults['database'] = $env->get('DB_DATABASE');
            $this->lineBreak();
            $this->line('Found an <comment>.env</comment> file in your current working directory, values will now be used as defaults.');
            $this->lineBreak();
        } else {
            $this->lineBreak();
            $this->line('Unable to find an <comment>.env</comment> file in your current working directory, now using the defaults.');
            $this->line('If these were changed in your <comment>docker-compose.yml</comment> file, please supply it as a command');
            $this->line('flag or add an <comment>.env</comment> file and add it there.');
            
            $this->lineBreak();
            $this->line('If you want to use the .env route, create a .env file and add the variables below including the values:');
            $this->line('DB_USERNAME=');
            $this->line('DB_PASSWORD=');
            $this->line('DB_DATABASE=');
            $this->lineBreak();
            $this->line('If your .env is located somewhere else, you may use the <comment>--env=/path/to/your/.env</comment> flag.');
            $this->line('For example, <comment>devstack mysql --env=/path/to/your/.env/directory</comment>.');
            $this->lineBreak();
            $this->line('Finally for the command flag, just do <comment>devstack mysql --user=the_user --password=the_password --database=the_db</comment>.');
            $this->line('For more details on the <comment>devsack mysql</comment> command flags, run <comment>devstack help mysql</comment>.');
            $this->lineBreak();
        }


        $password = $this->input->getOption('password') ?? $defaults['password'];
        $user = $this->input->getOption('user') ?? $defaults['user'];
        $database = $this->input->getOption('database') ?? $defaults['database'];

        $bashCommand = [];
        $bashCommand[] = "MYSQL_PWD={$password}";
        $bashCommand[] = "mysql -u {$user}";
        if ($database) {
            $bashCommand[] = $database;
        }

        $this->handleTerminationSignals(
            $process = Process::prepareFromShell('docker compose exec -it mysql bash -c "' . join(' ', $bashCommand) . '"')
        );

        $process->setTty(true)
            ->setTimeout(null)
            ->run();

        return static::SUCCESS;
    }
}