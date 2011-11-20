<?php

class erLhcoreClassModelGalleryMyfavoritesSession {
	
   const COMPARE_COOKIE_VARIABLE = 'HPPG_Myfavorites_images';
     
   public function getState()
   {
       return array(
               'id'             	=> $this->id,
               'user_id'   			=> $this->user_id,             
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
        	if (!(!empty($_COOKIE[erLhcoreClassModelGalleryMyfavoritesSession::COMPARE_COOKIE_VARIABLE]) && self::$instance = erLhcoreClassModelGalleryMyfavoritesSession::getSessionParams($_COOKIE[erLhcoreClassModelGalleryMyfavoritesSession::COMPARE_COOKIE_VARIABLE]))) {
        	    $currentUser = erLhcoreClassUser::instance();
        		if ( !($currentUser->isLogged() && self::$instance = self::getSessionByUser($currentUser->getUserID()))) {
        		    self::$instance = new erLhcoreClassModelGalleryMyfavoritesSession();
			        self::$instance->storeNewSession();
        		}
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
		$this->mtime = time()+2419200;		
		
		$currentUser = erLhcoreClassUser::instance();
		if ($currentUser->isLogged())
		   $user_id = $currentUser->getUserID();	
		else 
		    $user_id = 0;
		    
		$this->user_id = $user_id;
		    
		    
		erLhcoreClassGallery::getSession()->save($this);
								
		setcookie(erLhcoreClassModelGalleryMyfavoritesSession::COMPARE_COOKIE_VARIABLE,$this->session_hash,time()+2419200,'/');
					    	
	}
	
	public function clearFavoriteCache()
	{
		// Increase favourite cache version
       $cache = CSCacheAPC::getMem(); 
       $cache->increaseCacheVersion('favorite_'.$this->id);
	}
	
	public static function getSessionByUser($user_id)
	{
	    $session = erLhcoreClassGallery::getSession('slave');
       	$q = $session->createFindQuery( 'erLhcoreClassModelGalleryMyfavoritesSession' );        	
       	$q->where( $q->expr->eq( 'user_id', $q->bindValue( $user_id ) ) );
	    $q->limit(1,0);             	
       	$objects = $session->find( $q );
       	
       	if ( !empty($objects) ) {
       	    
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
		
	// Fills atributes if record exists
	public static function getSessionParams($SHA)
	{		
		if (strlen($_COOKIE[erLhcoreClassModelGalleryMyfavoritesSession::COMPARE_COOKIE_VARIABLE]) != 40) return false;		
		
		$session = erLhcoreClassGallery::getSession('slave');
       	$q = $session->createFindQuery( 'erLhcoreClassModelGalleryMyfavoritesSession' ); 
       	
       	$currentUser = erLhcoreClassUser::instance();
		if ($currentUser->isLogged())
		   $user_id = $currentUser->getUserID();	
		else 
		    $user_id = 0;	
        
		if ($user_id > 0){      	
	       	$q->where( $q->expr->lOr( $q->expr->eq( 'user_id', $q->bindValue( $user_id ) ), $q->expr->eq( 'session_hash', $q->bindValue( $SHA ) ) ) );
		} else {
			$q->where($q->expr->eq( 'session_hash', $q->bindValue( $SHA ) ) );
		}

       	$q->limit(10,0);
        $q->orderBy('user_id DESC' ); // First will be session with user ID 
             	
       	$objects = $session->find( $q );
   
		if (count($objects) > 0)
		{			
		    $sessionAssigned = false;
			foreach ($objects as $favouriteSession)
			{			
			    if ( $sessionAssigned === false ) { 
			        $sessionAssigned = $favouriteSession;
				    $favouriteSession->mtime = time();
				    if ( $favouriteSession->user_id == 0 ) {
				        $favouriteSession->user_id = $user_id;
				    }				
				    // For timestamp
                    erLhcoreClassGallery::getSession()->update($favouriteSession);						
				    setcookie(erLhcoreClassModelGalleryMyfavoritesSession::COMPARE_COOKIE_VARIABLE,$favouriteSession->session_hash,time()+2419200,'/');	
			    } else {
			        $sessionAssigned->addFavouriteSession($favouriteSession);
			    }
			}
			
			return 	$sessionAssigned;	
		}
		return false;	
	}
	
	// Reassing anonymous user session to current logged user
	public function addFavouriteSession(erLhcoreClassModelGalleryMyfavoritesSession $session)
	{
	   $db = ezcDbInstance::get('slave');
       $stmt = $db->prepare('UPDATE lh_gallery_myfavorites_images SET session_id = :session_id WHERE session_id = :old_session_id');
       $stmt->bindValue( ':session_id',$this->id); 
       $stmt->bindValue( ':old_session_id',$session->id);
       $stmt->execute();
       
       $session->removeThis();      
   }
   
   public function removeThis() {
       erLhcoreClassGallery::getSession()->delete($this);		
   }
    
   public $id = null;
   public $user_id = 0;
   public $session_hash = '';  
   public $mtime = '';
   
   private static $instance = null; 

}


?>