<?php

class erLhcoreClassLhMemcache extends Memcache
{
    public function __construct()
    {               
         $hosts = erConfigClassLhConfig::getInstance()->conf->getSetting( 'memecache', 'servers' );
         foreach ($hosts as $server) {
                $this->addServer($server['host'],$server['port'],$server['weight']);
         }
    }  
    
    public function __destruct()
    {
        $this->close();
    }   
}


?>