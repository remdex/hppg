<?php

class erLhcoreClassModelGalleryDuplicateCollection {
        
   public function getState()
   {
       return array(
               'id'        => $this->id,           
               'time'      => $this->time           
       );
   }
   
   public static function fetch($dcid)
   {
       try {
        $DuplicateCollection = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryDuplicateCollection', (int)$dcid );
       } catch (Exception $e){
       
       }
       return $DuplicateCollection;
   }
     
   public static function canDelete($dcid, $skipChecking = false)
   {
       $dc = erLhcoreClassModelGalleryDuplicateCollection::fetch($dcid);
        
       if ($skipChecking==true) return $dc;
                      
       return false;  
   }
   
   public function removeThis()
   {
   		foreach ($this->duplicate_images as $duplicate)
   		{
   			$duplicate->removeThis();
   		}
   		
   		erLhcoreClassGallery::getSession()->delete($this);
   }
   
   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   } 
   
   public static function getDuplicatesCount($params = array())
   {
       $session = erLhcoreClassGallery::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_gallery_duplicate_collection" );     
         
       $conditions = array();
       
       if (isset($params['filter']) && count($params['filter']) > 0)
       {
           foreach ($params['filter'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->eq( $field, $q->bindValue($fieldValue) );
           } 
      }  
      
      if (isset($params['filterin']) && count($params['filterin']) > 0)
       {
           foreach ($params['filterin'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->in( $field, $q->bindValue($fieldValue) );
           } 
      }     
       
      if (isset($params['filterlt']) && count($params['filterlt']) > 0)
       {
           foreach ($params['filterlt'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->lt( $field, $q->bindValue($fieldValue) );
           } 
      }
      
      if (isset($params['filtergt']) && count($params['filtergt']) > 0)
       {
           foreach ($params['filtergt'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->gt( $field,$q->bindValue( $fieldValue) );
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
   
   public static function getDuplicates($paramsSearch = array())
   {             
       $paramsDefault = array('limit' => 32, 'offset' => 0);
       
       $params = array_merge($paramsDefault,$paramsSearch);
       
       $session = erLhcoreClassGallery::getSession();
       $q = $session->createFindQuery( 'erLhcoreClassModelGalleryDuplicateCollection' );  
       
       $conditions = array(); 
       
             
      if (isset($params['filter']) && count($params['filter']) > 0)
      {                     
           foreach ($params['filter'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->eq( $field, $q->bindValue($fieldValue) );
           }
      } 
      
      if (isset($params['filterin']) && count($params['filterin']) > 0)
      {
           foreach ($params['filterin'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->in( $field, $q->bindValue($fieldValue) );
           } 
      }
      
      if (isset($params['filterlt']) && count($params['filterlt']) > 0)
      {
           foreach ($params['filterlt'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->lt( $field, $q->bindValue($fieldValue) );
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
                
      $q->orderBy(isset($params['sort']) ? $params['sort'] : 'id DESC' ); 
      
              
      $objects = $session->find( $q, 'erLhcoreClassModelGalleryDuplicateCollection' );
         
      return $objects; 
   }
   
   public function __get($var)
   {
   	switch ($var) {
   		case 'total_grouped':   			
   				$this->total_grouped = count($this->duplicate_images);
   				return $this->total_grouped;
   			break;
   	
   		case 'duplicate_images':   				
   				$this->duplicate_images = erLhcoreClassGallery::getSession()->getRelatedObjects( $this, "erLhcoreClassModelGalleryDuplicateImage" ); 
   				return $this->duplicate_images;
   			break;
   		default:
   			break;
   	}
   }
   
   public $id = null;   
   public $time = null;   
}

?>