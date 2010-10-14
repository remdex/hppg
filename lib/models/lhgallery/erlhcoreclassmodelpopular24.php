<?php

class erLhcoreClassModelGalleryPopular24 {
        
   public function getState()
   {
       return array(
               'pid'        => $this->pid,
               'added'      => $this->added,            
               'hits'       => $this->hits            
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
       try {
            $hitpopular = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryPopular24', (int)$pid );
       } catch (Exception $e){
            erLhcoreClassModule::redirect('/');
        exit;
       }
       return $hitpopular;
   }
    
   public function __get($variable)
   {
   		switch ($variable) {
   			case 'image':   				
   				try {
   					$this->image = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryImage', (int)$this->pid );
   				} catch (Exception $e) {
   					$this->image = false;
   				}
   				return $this->image;
   				break;
   		
   			default:
   				break;
   		}
   }
   
   public static function deleteExpired() 
   {
       $db = ezcDbInstance::get();       
       $dayAgo = time()-3600*(int)(erLhcoreClassModelSystemConfig::fetch('popularrecent_timeout')->current_value);          		
	   $stmt = $db->prepare('DELETE FROM lh_gallery_popular24 WHERE added < :dayago');
	   $stmt->bindValue( ':dayago',$dayAgo); 
	   $stmt->execute();
	   
	   // Expunge cache if some image was deleted
	   $cache = CSCacheAPC::getMem(); 
	   $cache->increaseCacheVersion('popularrecent_version');
   }
   
   public static function getImageCount($params = array())
   {
       $session = erLhcoreClassGallery::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(pid)" )->from( "lh_gallery_popular24" );     
         
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
   
   public static function getImages($paramsSearch = array())
   {             
       $paramsDefault = array('limit' => 32, 'offset' => 0);
       
       $params = array_merge($paramsDefault,$paramsSearch);
       
       $session = erLhcoreClassGallery::getSession();
       $q = $session->createFindQuery( 'erLhcoreClassModelGalleryPopular24' );  
       
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
      
      $q->orderBy(isset($params['sort']) ? $params['sort'] : 'hits DESC' ); 
      
      if (!isset($params['disable_sql_cache']))
      {
          $cache = CSCacheAPC::getMem();  
          $sql = erLhcoreClassGallery::multi_implode(',',$params);
          $cacheKey = isset($params['cache_key']) ? md5($sql.$params['cache_key']) : md5('site_version_'.$cache->getCacheVersion('sit_version').$sql);      
              
          if (($objects = $cache->restore($cacheKey)) === false)
          {
              $objects = $session->find( $q ); 
              $cache->store($cacheKey,$objects);
          }          
      }  else { $objects = $session->find( $q ); }
         
      $pids = array();
      foreach ($objects as $item){
          $pids[] = $item->pid;
      }
      
      $images = erLhcoreClassModelGalleryImage::getImages(array('filterin' => array('pid' => $pids)));
      foreach ($objects as $item){
          $item->image = $images[$item->pid];
      }
      
      return $objects; 
   }
   
   public $pid = 0;
   public $added = null;
   public $hits = 0;
   
}

?>