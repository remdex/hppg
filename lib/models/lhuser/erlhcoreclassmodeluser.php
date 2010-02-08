<?

class erLhcoreClassModelUser {
        
    public function getState()
   {
       return array(
               'id'           => $this->id,
               'username'     => $this->username,
               'password'     => $this->password,
               'email'        => $this->email,
               'lastactivity' => $this->lastactivity
       );
   }
   
   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   }
   
   public function setPassword($password)
   {
       $cfgSite = erConfigClassLhConfig::getInstance();
	   $secretHash = $cfgSite->conf->getSetting( 'site', 'secrethash' );
       $this->password = sha1($password.$secretHash.sha1($password));
   } 
   
   public static function userExists($username)
   {
       $db = ezcDbInstance::get();
       $stmt = $db->prepare('SELECT count(*) as foundusers FROM lh_users WHERE username = :username');
       $stmt->bindValue( ':username',$username);       
       $stmt->execute();
       $rows = $stmt->fetchAll();
       
       return $rows[0]['foundusers'] > 0;        
   }
   
   public static function userEmailExists($email)
   {
       $db = ezcDbInstance::get();
       $stmt = $db->prepare('SELECT count(*) as foundusers FROM lh_users WHERE email = :email');
       $stmt->bindValue( ':email',$email);       
       $stmt->execute();
       $rows = $stmt->fetchAll();
       
       return $rows[0]['foundusers'] > 0;        
   }
   
   public static function fetchUserByEmail($email)
   {
       $db = ezcDbInstance::get();
       $stmt = $db->prepare('SELECT id FROM lh_users WHERE email = :email');
       $stmt->bindValue( ':email',$email);       
       $stmt->execute();
       $rows = $stmt->fetchAll();
       
       return $rows[0]['id']; 
   }
      
    public $id = null;
    public $username = '';
    public $password = '';
    public $email = '';
    public $lastactivity = '';
}

?>