<?php

 class erLhcoreClassLazyDatabaseConfiguration implements ezcBaseConfigurationInitializer
 {
     public static function configureObject( $instance )
     {
         switch ( $instance )
         {
             case false: // Default instance
             {
                try {
                    $cfg = erConfigClassLhConfig::getInstance();
                    $db = ezcDbFactory::create( "mysql://{$cfg->conf->getSetting( 'db', 'user' )}:{$cfg->conf->getSetting( 'db', 'password' )}@{$cfg->conf->getSetting( 'db', 'host' )}:{$cfg->conf->getSetting( 'db', 'port' )}/{$cfg->conf->getSetting( 'db', 'database' )}" );
                    $db->query('SET NAMES utf8');   
                    return $db;
                } catch (Exception $e) { 
                  die('Cannot connect to database.') ;  
                }
               
             }
             
             case 'sqlite':
             return ezcDbFactory::create( 'sqlite://:memory:' );
         }
     }
 }

 


?>