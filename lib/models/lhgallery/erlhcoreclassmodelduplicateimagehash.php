<?php

class erLhcoreClassModelGalleryDuplicateImageHash {
        
   public function getState()
   {
       return array(        
               'pid'        			=> $this->pid,           
               'hash'                   => $this->hash          
       );
   }
   
   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   } 
   
   public static function fetch($pid)
   {
       $Image = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryDuplicateImageHash', (int)$pid );
       return $Image;
   }
   
   public static function deleteByPid($pid)
   {
   	   $q = ezcDbInstance::get()->createDeleteQuery();
	   $q->deleteFrom( 'lh_gallery_duplicate_image_hash' )->where( $q->expr->eq( 'pid', $pid ) );
	   $stmt = $q->prepare();
	   $stmt->execute();
   }
    public static function getImages($paramsSearch = array())
   {             
       $paramsDefault = array('limit' => 32, 'offset' => 0);
       
       $params = array_merge($paramsDefault,$paramsSearch);
       
       $session = erLhcoreClassGallery::getSession();
       $q = $session->createFindQuery( 'erLhcoreClassModelGalleryDuplicateImageHash' );  
       
       $conditions = array(); 
                    
          if (isset($params['filter']) && count($params['filter']) > 0)
          {                     
               foreach ($params['filter'] as $field => $fieldValue)
               {
                   $conditions[] = $q->expr->eq( $field, $q->bindValue($fieldValue ));
               }
          } 
          
          if (isset($params['filterin']) && count($params['filterin']) > 0)
          {
               foreach ($params['filterin'] as $field => $fieldValue)
               {
                   $conditions[] = $q->expr->in( $field, $fieldValue );
               } 
          }
          
          if (isset($params['filterlt']) && count($params['filterlt']) > 0)
          {
               foreach ($params['filterlt'] as $field => $fieldValue)
               {
                   $conditions[] = $q->expr->lt( $field, $q->bindValue($fieldValue ));
               } 
          }
          
          if (isset($params['filtergt']) && count($params['filtergt']) > 0)
          {
               foreach ($params['filtergt'] as $field => $fieldValue)
               {
                   $conditions[] = $q->expr->gt( $field, $q->bindValue($fieldValue) );
               } 
          }      
      
      if (count($conditions) > 0)
      {
          $q->where( 
                     $conditions   
          );
      } 
      
      $q->limit($params['limit'],$params['offset']);
                
      $q->orderBy(isset($params['sort']) ? $params['sort'] : 'pid DESC' ); 
          
      $objects = $session->find( $q );
         
      return $objects; 
   }
   
   public static function getImageCount($params = array())
   {
       $session = erLhcoreClassGallery::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(pid)" )->from( "lh_gallery_duplicate_image_hash" );     
         
       $conditions = array();
       
       if (isset($params['filter']) && count($params['filter']) > 0)
       {
           foreach ($params['filter'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->eq( $field, $q->bindValue($fieldValue ));
           } 
      }  
      
      if (isset($params['filterin']) && count($params['filterin']) > 0)
       {
           foreach ($params['filterin'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->in( $field, $fieldValue );
           } 
      }     
       
      if (isset($params['filterlt']) && count($params['filterlt']) > 0)
       {
           foreach ($params['filterlt'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->lt( $field,$q->bindValue($fieldValue ));
           } 
      }
      
      if (isset($params['filtergt']) && count($params['filtergt']) > 0)
       {
           foreach ($params['filtergt'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->gt( $field, $q->bindValue($fieldValue ));
           } 
      }
      
      if (count($conditions) > 0)
      {
          $q->where( 
                     $conditions   
          );
      }         
           
      $stmt = $q->prepare();       
      $stmt->execute();
      $result = $stmt->fetchColumn(); 
       
      return $result; 
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
   
 
   public $pid = null;   
   public $hash = null;   
}

?>