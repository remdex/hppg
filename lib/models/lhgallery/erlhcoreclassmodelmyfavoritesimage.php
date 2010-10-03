<?php

class erLhcoreClassModelGalleryMyfavoritesImage {
        
   public function getState()
   {
       return array(
               'id'             => $this->id,
               'session_id'     => $this->session_id,  
               'pid'        	=> $this->pid
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
       $imagesInSessions = erLhcoreClassModelGalleryMyfavoritesImage::getImages(array('limit' => 5000,'filter' => array('pid' => $pid)));
       foreach ($imagesInSessions as $imageFavourite) {
           $imageFavourite->removeThis();
       }
   }
   
   public static function getImageCount($params = array())
   {
       $session = erLhcoreClassGallery::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(pid)" )->from( "lh_gallery_myfavorites_images" );     
         
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
   
   public function __get($variable){
   	
   	switch ($variable) {
   		case 'image':
	   			$this->image = false;
	   			try {
	   			     $this->image = erLhcoreClassModelGalleryImage::fetch($this->pid);
	   			} catch (Exception $e){
	   			     $this->removeThis();
	   			     return false;
	   			}	   			   			
	   			return $this->image;
   			break;
   	
   		default:
   			break;
   	}
   	
   }
   
   public function removeThis() {
   	
   		$cache = CSCacheAPC::getMem(); 
        $cache->increaseCacheVersion('favorite_'.$this->session_id);
                
   		erLhcoreClassGallery::getSession()->delete($this);   		   		
   }
   
   public static function getImages($paramsSearch = array())
   {             
       $paramsDefault = array('limit' => 32, 'offset' => 0);
       
       $params = array_merge($paramsDefault,$paramsSearch);
       
       $session = erLhcoreClassGallery::getSession();
       $q = $session->createFindQuery( 'erLhcoreClassModelGalleryMyfavoritesImage' );  
       
       $conditions = array(); 
       if (!isset($paramsSearch['smart_select'])) {
             
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
                           $conditions[] = $q->expr->in( $field, $fieldValue);
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
                           $conditions[] = $q->expr->gt( $field, $q->bindValue($fieldValue ));
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
       } else {
           $q2 = $q->subSelect();
           $q2->select( 'pid' )->from( 'lh_gallery_myfavorites_images' );
           
           if (isset($params['filter']) && count($params['filter']) > 0)
          {                     
               foreach ($params['filter'] as $field => $fieldValue)
               {
                   $conditions[] = $q2->expr->eq( $field, $q->bindValue($fieldValue) );
               }
          } 
          
          if (isset($params['filterin']) && count($params['filterin']) > 0)
          {
               foreach ($params['filterin'] as $field => $fieldValue)
               {
                   $conditions[] = $q2->expr->in( $field, $fieldValue );
               } 
          }
          
          if (isset($params['filterlt']) && count($params['filterlt']) > 0)
          {
               foreach ($params['filterlt'] as $field => $fieldValue)
               {
                   $conditions[] = $q2->expr->lt( $field, $q->bindValue($fieldValue) );
               } 
          }
          
          if (isset($params['filtergt']) && count($params['filtergt']) > 0)
          {
               foreach ($params['filtergt'] as $field => $fieldValue)
               {
                   $conditions[] = $q2->expr->gt( $field, $q->bindValue($fieldValue) );
               } 
          }      
          
          if (count($conditions) > 0)
          {
              $q2->where( 
                         $conditions   
              );
          }
           
          $q2->limit($params['limit'],$params['offset']);
          $q2->orderBy(isset($params['sort']) ? $params['sort'] : 'pid DESC');
          $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_myfavorites_images.pid', 'items.pid' );          
       }
       
       
      if (!isset($params['disable_sql_cache']))
      {
          $cache = CSCacheAPC::getMem();  
          $sql = erLhcoreClassGallery::multi_implode(',',$params);
          $cacheKey = isset($params['cache_key']) ? md5($sql.$params['cache_key']) : md5('site_version_'.$cache->getCacheVersion('sit_version').$sql);      
              
          if (($objects = $cache->restore($cacheKey)) === false)
          {
              $objects = $session->find( $q, 'erLhcoreClassModelGalleryMyfavoritesImage' ); 
              $cache->store($cacheKey,$objects);
          }          
      }  else { $objects = $session->find( $q, 'erLhcoreClassModelGalleryMyfavoritesImage' ); }
         
      return $objects; 
   }
        
   public $id = null;
   public $session_id = '';  
   public $pid = '';
   
   private static $instance = null; 

}


?>