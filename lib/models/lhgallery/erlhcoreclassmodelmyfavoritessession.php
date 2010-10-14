<?php

class erLhcoreClassModelGalleryMyfavoritesSession {
	
   const COMPARE_COOKIE_VARIABLE = 'HPPG_Myfavorites_images';
     
   public function getState()
   {
       return array(
               'id'             	=> $this->id,
               'user_id'   			=> $this->user_id,             
               'session_hash_crc32' => $this->session_hash_crc32,             
               'session_hash'       => $this->session_hash,             
               'mtime'        		=> $this->mtime
       );
   }
      
   public function __construct()
   {   			
	
   }
      
   public static function getInstance()  
   {
        if ( is_null( self::$instance ) )
        {          
        	if (!(!empty($_COOKIE[erLhcoreClassModelGalleryMyfavoritesSession::COMPARE_COOKIE_VARIABLE]) && self::$instance = erLhcoreClassModelGalleryMyfavoritesSession::getSessionParams($_COOKIE[erLhcoreClassModelGalleryMyfavoritesSession::COMPARE_COOKIE_VARIABLE])))		
			{
			    self::$instance = new erLhcoreClassModelGalleryMyfavoritesSession();
			    self::$instance->storeNewSession();
			}	
        }
        
        return self::$instance;
   }
    
   
   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   }
   		
	// Starting new session
	function storeNewSession()
	{	
		$RandomString = mt_rand().time();	
			
		$this->session_hash = sha1($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].$RandomString);	
		$this->session_hash_crc32 = abs(crc32(sha1($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].$RandomString)));			
		$this->mtime = time()+2419200;		
				
		erLhcoreClassGallery::getSession()->save($this);
								
		setcookie(erLhcoreClassModelGalleryMyfavoritesSession::COMPARE_COOKIE_VARIABLE,$this->session_hash,time()+2419200,'/');
					    	
	}
	
	public function clearFavoriteCache()
	{
		// Clear album cache
       $cache = CSCacheAPC::getMem(); 
       $cache->increaseCacheVersion('favorite_'.$this->id);
	}
	
	// Fills atributes if record exists
	public static function getSessionParams($SHA)
	{		
		if (strlen($_COOKIE[erLhcoreClassModelGalleryMyfavoritesSession::COMPARE_COOKIE_VARIABLE]) != 40) return false;		
		
		$session = erLhcoreClassGallery::getSession();
       	$q = $session->createFindQuery( 'erLhcoreClassModelGalleryMyfavoritesSession' ); 
       	
       	$currentUser = erLhcoreClassUser::instance();
		if ($currentUser->isLogged())
		   $user_id = $currentUser->getUserID();	
		else 
		    $user_id = 0;	
        
		if ($user_id > 0){      	
	       	$q->where( $q->expr->lOr( $q->expr->eq( 'user_id', $q->bindValue( $user_id ) ),       	
	       								$q->expr->lAnd(
	       									$q->expr->eq( 'session_hash_crc32', $q->bindValue( abs(crc32($SHA)) ) ),
	       									$q->expr->eq( 'session_hash', $q->bindValue( $SHA ) )
	       								)                                      
	                                       ) );
		} else {
			$q->where(  $q->expr->eq( 'session_hash_crc32', $q->bindValue( abs(crc32($SHA)) ) ),
						$q->expr->eq( 'session_hash', $q->bindValue( $SHA ) )
					                                      
                    );
		}

       	$q->limit(1,0);
             	
       	$objects = $session->find( $q );
     
		if (count($objects) > 0)
		{			
			foreach ($objects as $favouriteSession)
			{				
				$favouriteSession->mtime = time();
				// For timestamp
                erLhcoreClassGallery::getSession()->update($favouriteSession);
						
				setcookie(erLhcoreClassModelGalleryMyfavoritesSession::COMPARE_COOKIE_VARIABLE,$favouriteSession->session_hash,time()+2419200,'/');	
			}	
			
			return 	$favouriteSession;	
		}
		return false;	
	}
	
    
   public $id = null;
   public $user_id = 0;
   public $session_hash = '';  
   public $session_hash_crc32 = '';  
   public $mtime = '';
   
   private static $instance = null; 

}


?>