<?php

class erLhcoreClassModelGalleryUpload {
        
   public function getState()
   {
       return array(
               'id'             => $this->id,
               'album_id'       => $this->album_id,             
               'hash'           => $this->hash,             
               'created'        => $this->created,
               'user_id'        => $this->user_id
       );
   }
   
   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   } 
    
   public $id = null;
   public $album_id = null;
   public $hash = '';
   public $created = '';
   public $user_id = '';

}


?>