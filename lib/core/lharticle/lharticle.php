<?php

class erLhcoreClassArticle {
         
   public static function getSession()
   {
        if ( !isset( self::$persistentSession ) )
        {            
            self::$persistentSession = new ezcPersistentSession(
                ezcDbInstance::get(),
                new ezcPersistentCodeManager( './pos/lharticle' )
            );
        }
        return self::$persistentSession;
   }
    
   private static $persistentSession;
}

?>