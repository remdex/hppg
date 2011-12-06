<?php

class erLhcoreClassLazyDatabaseConfiguration implements ezcBaseConfigurationInitializer
{     
     private static $connectionMaster;

     public static function configureObject( $instance )
     {         
         $cfg = erConfigClassLhConfig::getInstance();
         switch ( $instance )
         {            
             case 'slave':                 
                 if ($cfg->getSetting( 'db', 'use_slaves' ) === true) {
                     try {
        		         $dbSlaves = $cfg->getSetting( 'db', 'db_slaves' );
        		         $slaveParams = $dbSlaves[rand(0,count($dbSlaves)-1)];
                         $db = ezcDbFactory::create( "mysql://{$slaveParams['user']}:{$slaveParams['password']}@{$slaveParams['host']}:{$slaveParams['port']}/{$slaveParams['database']}" );
                         $db->query('SET NAMES utf8'); 
                     } catch (Exception $e){
                         die('Cannot connect to database.') ;  
                     }
                     return $db;                     
                 } else {       
                     // Perhaps connection is already done with master?            
                     if (isset(self::$connectionMaster)) return self::$connectionMaster; 
                     try {
                        $db = ezcDbFactory::create( "mysql://{$cfg->getSetting( 'db', 'user' )}:{$cfg->getSetting( 'db', 'password' )}@{$cfg->getSetting( 'db', 'host' )}:{$cfg->getSetting( 'db', 'port' )}/{$cfg->getSetting( 'db', 'database' )}" );
                        $db->query('SET NAMES utf8'); 
                        self::$connectionMaster = $db;
                        return $db;
                    } catch (Exception $e) { 
                      die('Cannot connect to database.') ;  
                    }
                 }                
                 break;
               
             case false: // Default instance
             {
                try {
                    if (isset(self::$connectionMaster)) return self::$connectionMaster; // If we do not user slaves and slave request already got connection                    
                    $db = ezcDbFactory::create( "mysql://{$cfg->getSetting( 'db', 'user' )}:{$cfg->getSetting( 'db', 'password' )}@{$cfg->getSetting( 'db', 'host' )}:{$cfg->getSetting( 'db', 'port' )}/{$cfg->getSetting( 'db', 'database' )}" );
                    $db->query('SET NAMES utf8'); 
                    self::$connectionMaster = $db;
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