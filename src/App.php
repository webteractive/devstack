<?php

namespace Webteractive\Devstack;

use Symfony\Component\Console\Application;

class App extends Application
{
    private static $name = "MyApp";
    private static $logo = <<<LOGO
    __| | _____   _____| |_ __ _  ___| | __
   / _` |/ _ \ \ / / __| __/ _` |/ __| |/ /
  | (_| |  __/\ V /\__ \ || (_| | (__|   < 
   \__,_|\___| \_/ |___/\__\__,_|\___|_|\_\  
LOGO;

    public function __construct($name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        $this->setName(static::$name);
        $this->setVersion($version);
        parent::__construct($name, $version);
    }

    /**
     * @return string
     */
    public function getHelp(): string
    {
        return static::$logo . "\n\n" . parent::getHelp();
    }
}
