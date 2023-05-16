<?php

namespace Webteractive\Devstack;

use Symfony\Component\Console\Application;

class App extends Application
{
    private static $name = "MyApp";
    private static $logo = <<<LOGO
    DDDDDDDDDDDDD                                                                           tttt                                               kkkkkkkk           
    D::::::::::::DDD                                                                     ttt:::t                                               k::::::k           
    D:::::::::::::::DD                                                                   t:::::t                                               k::::::k           
    DDD:::::DDDDD:::::D                                                                  t:::::t                                               k::::::k           
      D:::::D    D:::::D     eeeeeeeeeeee  vvvvvvv           vvvvvvv  ssssssssss   ttttttt:::::ttttttt      aaaaaaaaaaaaa      cccccccccccccccc k:::::k    kkkkkkk
      D:::::D     D:::::D  ee::::::::::::ee v:::::v         v:::::v ss::::::::::s  t:::::::::::::::::t      a::::::::::::a   cc:::::::::::::::c k:::::k   k:::::k 
      D:::::D     D:::::D e::::::eeeee:::::eev:::::v       v:::::vss:::::::::::::s t:::::::::::::::::t      aaaaaaaaa:::::a c:::::::::::::::::c k:::::k  k:::::k  
      D:::::D     D:::::De::::::e     e:::::e v:::::v     v:::::v s::::::ssss:::::stttttt:::::::tttttt               a::::ac:::::::cccccc:::::c k:::::k k:::::k   
      D:::::D     D:::::De:::::::eeeee::::::e  v:::::v   v:::::v   s:::::s  ssssss       t:::::t              aaaaaaa:::::ac::::::c     ccccccc k::::::k:::::k    
      D:::::D     D:::::De:::::::::::::::::e    v:::::v v:::::v      s::::::s            t:::::t            aa::::::::::::ac:::::c              k:::::::::::k     
      D:::::D     D:::::De::::::eeeeeeeeeee      v:::::v:::::v          s::::::s         t:::::t           a::::aaaa::::::ac:::::c              k:::::::::::k     
      D:::::D    D:::::D e:::::::e                v:::::::::v     ssssss   s:::::s       t:::::t    tttttta::::a    a:::::ac::::::c     ccccccc k::::::k:::::k    
    DDD:::::DDDDD:::::D  e::::::::e                v:::::::v      s:::::ssss::::::s      t::::::tttt:::::ta::::a    a:::::ac:::::::cccccc:::::ck::::::k k:::::k   
    D:::::::::::::::DD    e::::::::eeeeeeee         v:::::v       s::::::::::::::s       tt::::::::::::::ta:::::aaaa::::::a c:::::::::::::::::ck::::::k  k:::::k  
    D::::::::::::DDD       ee:::::::::::::e          v:::v         s:::::::::::ss          tt:::::::::::tt a::::::::::aa:::a cc:::::::::::::::ck::::::k   k:::::k 
    DDDDDDDDDDDDD            eeeeeeeeeeeeee           vvv           sssssssssss              ttttttttttt    aaaaaaaaaa  aaaa   cccccccccccccccckkkkkkkk    kkkkkkk
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
