<?php

class erLhcoreClassModelGalleryDuplicateImage {
        
   public function getState()
   {
       return array(
               'id'        				=> $this->id,           
               'pid'        			=> $this->pid,           
               'duplicate_collection_id'=> $this->duplicate_collection_id          
       );
   }
   
   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   } 
   
   public static function deleteByPid($pid)
   {
   	   $q = ezcDbInstance::get()->createDeleteQuery();
	   $q->deleteFrom( 'lh_gallery_duplicate_image' )->where( $q->expr->eq( 'pid', $pid ) );
	   $stmt = $q->prepare();
	   $stmt->execute();
   }
   
   public function removeThis()
   {
   		erLhcoreClassGallery::getSession()->delete($this);
   }
   
   public function __get($var)
   {
	   	switch ($var) {
	   		case 'image':   
	   			try {			
	   				$this->image = erLhcoreClassModelGalleryImage::fetch($this->pid);
	   				return $this->image;
	   			} catch (Exception $e) {
	   				return new erLhcoreClassModelGalleryImage();
	   			}
	   			break;
	   		   	
	   		default:
	   			break;
	   	}
   }
   
   public $id = null;   
   public $pid = null;   
   public $duplicate_collection_id = null;   
}

?>