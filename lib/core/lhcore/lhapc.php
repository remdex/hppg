<?php

class erLhcoreClassLhAPC
{
    public function set($key,$value,$compress,$ttl)
    {
        apc_store($key,$value,$ttl);
    }
    
    public function get($var)
    {
        return apc_fetch($var);
    }
    
    public function increment($var)
    {
        apc_inc($var);
    }
}


?>