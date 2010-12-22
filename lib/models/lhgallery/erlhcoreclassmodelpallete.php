<?php

class erLhcoreClassModelGalleryPallete {
        
   public function getState()
   {
       return array(
               'id'          => $this->id,
               'red'         => $this->red,             
               'green'       => $this->green,             
               'blue'        => $this->blue,            
               'position'    => $this->position           
       );
   }
   
   public static function fetch($pallete_id)
   {
       $pallete = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryPallete', (int)$pallete_id );
       return $pallete;
   }
   
   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   } 
   
   public function saveThis()
   {
       erLhcoreClassGallery::getSession()->saveOrUpdate($this);
   }
   
   public static function getListCount($params = array())
   {
       $session = erLhcoreClassGallery::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_gallery_pallete" );     
         
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
               $conditions[] = $q->expr->in( $field,  $fieldValue );
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
   
   public static function getListCountPalleteImages($params = array())
   {
       if (!isset($params['disable_sql_cache']))
       {
          $sql = erLhcoreClassGallery::multi_implode(',',$params);  
                       
          $cache = CSCacheAPC::getMem();          
          $cacheKey = isset($params['cache_key']) ? md5($sql.$params['cache_key']) : md5('images_pallete_count_site_version_'.$cache->getCacheVersion('site_version').$sql);
          
          if (($result = $cache->restore($cacheKey)) !== false)
          {              
              return $result;
          }       
       }
       
       $session = erLhcoreClassGallery::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(lh_gallery_pallete_images.pid)" )->from( "lh_gallery_pallete_images" ); 
       
     
       $conditions = array();
       
       $colors_count = count($params['pallete_id']);
       
       // Standard query if one pallete
       if ($colors_count == 1) {     
           $params['filter']['pallete_id'] = $params['pallete_id'][0];           
       } elseif ($colors_count > 1) {
                
          $conditions[] = $q->expr->eq( 'lh_gallery_pallete_images.pallete_id', $q->bindValue($params['pallete_id'][0]) );
          for ($i = 1;$i < $colors_count; $i++) {
               $q->innerJoin( $q->alias( 'lh_gallery_pallete_images', 'color_'.$i ), 'lh_gallery_pallete_images.pid', 'color_'.$i.'.pid' );
               $conditions[] =  $q->expr->eq( 'color_'.$i.'.pallete_id', $q->bindValue($params['pallete_id'][$i]) );
          }
       }   
       
       
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
               $conditions[] = $q->expr->in( $field,  $fieldValue );
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
       
      
      if (!isset($params['disable_sql_cache'])) {
              $cache->store($cacheKey,$result);           
      }
         
      return $result; 
   }
   
   
   
   public static function getPictureCountByPalleteId($pid,$pallete_id)
   {
       $session = erLhcoreClassGallery::getSession();
       
       $q = $session->database->createSelectQuery();
         
       $q->select( "`count`" )->from( "lh_gallery_pallete_images" ); 
       $q->where(
            $q->expr->eq( 'pid', $q->bindValue($pid) ),  
            $q->expr->eq( 'pallete_id', $q->bindValue($pallete_id) )  
       );
       
       $stmt = $q->prepare();       
       $stmt->execute();
       $result = $stmt->fetchColumn();
       
       return $result; 
       
   }
   
   // Returns picture dominant colors palletes
   public static function getPictureDominantColors($pid)
   {
              
       $db = ezcDbInstance::get(); 
       $stmt = $db->prepare('SELECT colors FROM lh_gallery_pallete_images_stats WHERE pid = :pid');
       $stmt->bindValue( ':pid',$pid);            
       $stmt->execute();            
       $stats = $stmt->fetchColumn();  
       
       $result = array();
       if ($stats !== null) {   
            $session = erLhcoreClassGallery::getSession();      
              
            $statsImploded = explode(',',$stats); 

            $q = $session->createFindQuery( 'erLhcoreClassModelGalleryPallete' );

            $q->where( $q->expr->in( 'id', $statsImploded ) );
                              
            $objects = $session->find( $q );  
                                    
            $result = array()     ;
            foreach ($statsImploded as $stat)
            {
                $result[] = isset($objects[$stat]) ? $objects[$stat] : null;
            }
            
            $result = array_filter($result);
       }
       

       return $result;  
   }
     
   public static function getList($paramsSearch = array())
   {
       $paramsDefault = array('limit' => 500, 'offset' => 0);
       
       $params = array_merge($paramsDefault,$paramsSearch);
       
       $session = erLhcoreClassGallery::getSession();
       $q = $session->createFindQuery( 'erLhcoreClassModelGalleryPallete' );  
       
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
               $conditions[] = $q->expr->in( $field, $fieldValue );
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
      $q->orderBy(isset($params['sort']) ? $params['sort'] : 'position DESC' ); 
       
      $objects = $session->find( $q );
                           
      return $objects; 
   }
   
   public static function getAjaxImages($pid,$pallete_ids,$direction)
   {
       
       $count_palletes_ids = count($pallete_ids);
       
       if ($count_palletes_ids == 1) {
            
            $pallete_id = $pallete_ids[0];
            $session = erLhcoreClassGallery::getSession();
        	$q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );     	
        	$q2 = $q->subSelect();
            $q2->select( 'pid' )->from( 'lh_gallery_pallete_images' );        
            $colorMatchedTimes = erLhcoreClassModelGalleryPallete::getPictureCountByPalleteId($pid,$pallete_id);    	          	
        	if ($direction == 'left'){
        	    $q2->where(  $q2->expr->eq( 'pallete_id', (int)$pallete_id ).' AND ('.$q2->expr->gt( 'count', $q2->bindValue( $colorMatchedTimes ) ). ' OR '.$q2->expr->eq( 'count', $q2->bindValue( $colorMatchedTimes ) ).' AND '.$q2->expr->gt( 'pid', $q2->bindValue( $pid ) ).')' )
                    ->orderBy('count ASC, pid ASC')
                    ->limit( 6 );
                    $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
                    $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                    $imagesAjax = array_reverse($imagesAjax);
        	} else {
        	     $q2->where(  $q2->expr->eq( 'pallete_id', (int)$pallete_id ).' AND ('.$q2->expr->lt( 'count', $q2->bindValue( $colorMatchedTimes ) ). ' OR '.$q2->expr->eq( 'count', $q2->bindValue( $colorMatchedTimes ) ).' AND '.$q2->expr->lt( 'pid', $q2->bindValue( $pid ) ).')' )
                    ->orderBy('count DESC, pid DESC')
                    ->limit( 6 );
                    $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
                    $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
        	}; 
        	
        	return $imagesAjax;	
        } elseif ($count_palletes_ids > 1) { //If more than one color filter is used
            
            if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'memory_table') == false) {
              
                $orderParts = array();
                $orderParts[] = 'LOG(lh_gallery_pallete_images.count)';
                for ($i = 1;$i < $count_palletes_ids; $i++) {
                      $orderParts[] = 'LOG(color_'.$i.'.count)';  
                }
                            
                // We have to find out our image match coeficient
               $session = erLhcoreClassGallery::getSession();       
               $q = $session->database->createSelectQuery();                 
               $q->select( 'round(('.implode('+',$orderParts).')*1000) AS count' )->from( "lh_gallery_pallete_images" ); 
               
               $conditions[] = $q->expr->eq( 'lh_gallery_pallete_images.pid', $q->bindValue($pid) );
               $conditions[] = $q->expr->eq( 'lh_gallery_pallete_images.pallete_id', $q->bindValue($pallete_ids[0]) );
               
               for ($i = 1;$i < $count_palletes_ids; $i++) {
                   $q->innerJoin( $q->alias( 'lh_gallery_pallete_images', 'color_'.$i ), 'lh_gallery_pallete_images.pid', 'color_'.$i.'.pid' );
                   $conditions[] =  $q->expr->eq( 'color_'.$i.'.pallete_id', $q->bindValue($pallete_ids[$i]) );                              
               } 
              
               $q->where(
                    $conditions
               );
               
               $stmt = $q->prepare();       
               $stmt->execute();
               $colorMatchedTimes = $stmt->fetchColumn(); // Current image match coeficient
                                       
               
               // Left images fetch
               $q = $session->database->createSelectQuery();               
               $q->select( "*" )->from( "lh_gallery_images" );  
                
               $q2 = $q->subSelect();
               $q2->select( 'lh_gallery_pallete_images.pid' )->from( 'lh_gallery_pallete_images' );
               
               $conditions = array();
               $conditions[] = $q2->expr->eq( 'lh_gallery_pallete_images.pallete_id', $q2->bindValue($pallete_ids[0]) );
               for ($i = 1;$i < $count_palletes_ids; $i++) {
                   $q2->innerJoin( $q2->alias( 'lh_gallery_pallete_images', 'color_'.$i ), 'lh_gallery_pallete_images.pid', 'color_'.$i.'.pid' );
                   $conditions[] =  $q2->expr->eq( 'color_'.$i.'.pallete_id', $q2->bindValue($pallete_ids[$i]) );               
               }  
               
               if ($direction == 'left') {                   
                   $q2->where(implode(' AND ',$conditions).' AND ('.$q2->expr->gt( 'round(('.implode('+',$orderParts).')*1000)', $q2->bindValue( $colorMatchedTimes ) ). ' OR '.$q2->expr->eq( 'round(('.implode('+',$orderParts).')*1000)', $q2->bindValue( $colorMatchedTimes ) ).' AND '.$q2->expr->gt( 'lh_gallery_pallete_images.pid', $q2->bindValue( $pid ) ).')');
                   $q2->orderBy(implode('+',$orderParts).' ASC, lh_gallery_pallete_images.pid ASC');              
                   $q2->limit(6);              
                   $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );              
                   $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );          
                   $imagesAjax = array_reverse($imagesAjax); 
                              	               	                                                    
            	} else {    
            	            	    
            	    $q2->where(implode(' AND ',$conditions).' AND ('.$q2->expr->lt( 'round(('.implode('+',$orderParts).')*1000)', $q2->bindValue( $colorMatchedTimes ) ). ' OR '.$q2->expr->eq( 'round(('.implode('+',$orderParts).')*1000)', $q2->bindValue( $colorMatchedTimes ) ).' AND '.$q2->expr->lt( 'lh_gallery_pallete_images.pid', $q2->bindValue( $pid ) ).')');
                    $q2->orderBy(implode('+',$orderParts).' DESC, lh_gallery_pallete_images.pid DESC');              
                    $q2->limit(6);              
                    $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );              
                    $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );                    
               };
               
               return $imagesAjax;
          } else {
              
                /**
                   * Let push maximum parformance from Mysql
                   * */                
                $selectFields = array();
                $selectFields[] = 'LOG(`lh_gallery_pallete_images`.count)';
                $db = ezcDbInstance::get();   
                                      
                $innerJoins = array();
                $conditions[] = 'lh_gallery_pallete_images.pallete_id = '.(int)$pallete_ids[0];
                for ($i = 1;$i < $count_palletes_ids; $i++) {
                   $innerJoins[] = 'INNER JOIN `lh_gallery_pallete_images` AS '.'color_'.$i.' ON '.'color_'.$i.'.pid =  `lh_gallery_pallete_images`.pid';
                   $conditions[] =  'color_'.$i.'.pallete_id = '.(int)$pallete_ids[$i];
                   $selectFields[] = 'LOG(color_'.$i.'.count)';                   
                }
                                                
                // We create memory table and insert to it instantly
                $sql = "CREATE TEMPORARY TABLE color_search (
                pid INT( 10 ) UNSIGNED NOT NULL ,
                count INT( 6 ) UNSIGNED NOT NULL,
                INDEX USING BTREE (count,pid),
                INDEX USING BTREE (pid)
                ) ENGINE = MEMORY;
                INSERT INTO color_search (SELECT `lh_gallery_pallete_images`.pid,(".implode('+',$selectFields).")*1000 FROM `lh_gallery_pallete_images` ".implode(' ',$innerJoins)." WHERE ".implode(' AND ',$conditions).");";               
                $conditions = array(); 
                $stmt = $db->prepare($sql);
                $stmt->execute();
                
                // We fetch from memory table current image match status
                $session = erLhcoreClassGallery::getSession();
       
                $q = $session->database->createSelectQuery();
                 
                $q->select( "`count`" )->from( "color_search" ); 
                $q->where(
                    $q->expr->eq( 'pid', $q->bindValue($pid) ) 
                );
               
                $stmt = $q->prepare();       
                $stmt->execute();
                $colorMatchedTimes = $stmt->fetchColumn();
        
                
                $db = ezcDbInstance::get(); 
                $session = erLhcoreClassGallery::getSession(); 
                
                $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
                $q2 = $q->subSelect();
                $q2->select( 'pid' )->from( 'color_search' );
                
                if ($direction == 'left') {
                    $q2->where( ' ('.$q2->expr->gt( 'count', $q2->bindValue( $colorMatchedTimes ) ). ' OR '.$q2->expr->eq( 'count', $q2->bindValue( $colorMatchedTimes ) ).' AND '.$q2->expr->gt( 'pid', $q2->bindValue( $pid ) ).')' )
                    ->orderBy('count ASC, pid ASC')
                    ->limit( 6 );                                
                    $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );                
                    $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                    $imagesAjax = array_reverse($imagesAjax); 
                } else {
                    $q2->where( ' ('.$q2->expr->lt( 'count', $q2->bindValue( $colorMatchedTimes ) ). ' OR '.$q2->expr->eq( 'count', $q2->bindValue( $colorMatchedTimes ) ).' AND '.$q2->expr->lt( 'pid', $q2->bindValue( $pid ) ).')' )
                    ->orderBy('count DESC, pid DESC')
                    ->limit( 6 );                                
                    $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );                
                    $imagesAjax = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                }
                
                $stmt = $db->prepare('DROP TABLE color_search');
                $stmt->execute();
               
                return $imagesAjax;                                               
          } 
        }
   }

   /**
    * @desc Used in image preview window, gets left and right images, also page for return to thumbnails
    * 
    * @param Image pid
    * @param used_pallete
    * 
    * */ 
   public static function getPreviewData($pid,$pallete_ids)
   {
       $count_palletes_ids = count($pallete_ids);
       // If one color, use standard query
       if ($count_palletes_ids == 1) {
           
           $pallete_id = $pallete_ids[0];
           
           $colorMatchedTimes = erLhcoreClassModelGalleryPallete::getPictureCountByPalleteId($pid,$pallete_id);
           
           $db = ezcDbInstance::get(); 
           $session = erLhcoreClassGallery::getSession(); 
                
           $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
           $q2 = $q->subSelect();
           $q2->select( 'pid' )->from( 'lh_gallery_pallete_images' );
                       
           $q2->where( $q2->expr->eq( 'pallete_id', (int)$pallete_id ).' AND ('.$q2->expr->gt( 'count', $q2->bindValue( $colorMatchedTimes ) ). ' OR '.$q2->expr->eq( 'count', $q2->bindValue( $colorMatchedTimes ) ).' AND '.$q2->expr->gt( 'pid', $q2->bindValue( $pid ) ).')' )
           ->orderBy('count ASC, pid ASC')
           ->limit( 5 );
            
           $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
            
           $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                 
           $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
           $q2 = $q->subSelect();
           $q2->select( 'pid' )->from( 'lh_gallery_pallete_images' );    
              
           $q2->where( $q2->expr->eq( 'pallete_id', (int)$pallete_id ).' AND('.$q2->expr->lt( 'count', $q2->bindValue( $colorMatchedTimes ) ). ' OR '.$q2->expr->eq( 'count', $q2->bindValue( $colorMatchedTimes ) ).' AND '.$q2->expr->lt( 'pid', $q2->bindValue( $pid ) ).')' )
           ->orderBy('count DESC, pid DESC')
           ->limit( 5 );
            
           $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
           $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                                
           $stmt = $db->prepare('SELECT count(pid) FROM lh_gallery_pallete_images WHERE (count > :count OR count = :count AND pid > :pid) AND pallete_id = :pallete_id LIMIT 1');
           $stmt->bindValue( ':count',$colorMatchedTimes);
           $stmt->bindValue( ':pallete_id',(int)$pallete_id);
           $stmt->bindValue( ':pid',$pid);
                 
           $stmt->execute();          
           $photos = $stmt->fetchColumn();
                
           $page = ceil(($photos+1)/20);
           
           return array('page' => $page,'imagesLeft' => $imagesLeft,'imagesRight' => $imagesRight);
           
       } elseif ($count_palletes_ids > 1) { //If more than one color filter is used
           
            /**
             * Logarithm helps to reduce risk of overflow log(ab) = log(a) + log(b)
             * In first case we do not use memory table, so in most cases temporary table is created
             * 
             * In second case we use memory table.
             * */
            if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'memory_table') == false) {
              
                $orderParts = array();
                $orderParts[] = 'LOG(lh_gallery_pallete_images.count)';
                for ($i = 1;$i < $count_palletes_ids; $i++) {
                      $orderParts[] = 'LOG(color_'.$i.'.count)';  
                }
            
                
                // We have to find out our image match coeficient
               $session = erLhcoreClassGallery::getSession();       
               $q = $session->database->createSelectQuery();                 
               $q->select( 'round(('.implode('+',$orderParts).')*1000) AS count' )->from( "lh_gallery_pallete_images" ); 
               
               $conditions[] = $q->expr->eq( 'lh_gallery_pallete_images.pid', $q->bindValue($pid) );
               $conditions[] = $q->expr->eq( 'lh_gallery_pallete_images.pallete_id', $q->bindValue($pallete_ids[0]) );
               
               for ($i = 1;$i < $count_palletes_ids; $i++) {
                   $q->innerJoin( $q->alias( 'lh_gallery_pallete_images', 'color_'.$i ), 'lh_gallery_pallete_images.pid', 'color_'.$i.'.pid' );
                   $conditions[] =  $q->expr->eq( 'color_'.$i.'.pallete_id', $q->bindValue($pallete_ids[$i]) );                              
               } 
              
               $q->where(
                    $conditions
               );
               
               $stmt = $q->prepare();       
               $stmt->execute();
               $colorMatchedTimes = $stmt->fetchColumn(); // Current image match coeficient
                                        
               
               // Left images fetch
               $q = $session->database->createSelectQuery();               
               $q->select( "*" )->from( "lh_gallery_images" );  
                
               $q2 = $q->subSelect();
               $q2->select( 'lh_gallery_pallete_images.pid' )->from( 'lh_gallery_pallete_images' );
                
               $conditions = array();
               $conditions[] = $q2->expr->eq( 'lh_gallery_pallete_images.pallete_id', $q2->bindValue($pallete_ids[0]) );
               for ($i = 1;$i < $count_palletes_ids; $i++) {
                   $q2->innerJoin( $q2->alias( 'lh_gallery_pallete_images', 'color_'.$i ), 'lh_gallery_pallete_images.pid', 'color_'.$i.'.pid' );
                   $conditions[] =  $q2->expr->eq( 'color_'.$i.'.pallete_id', $q2->bindValue($pallete_ids[$i]) );
               
               }  
               
               $q2->where(implode(' AND ',$conditions).' AND ('.$q2->expr->gt( 'round(('.implode('+',$orderParts).')*1000)', $q2->bindValue( $colorMatchedTimes ) ). ' OR '.$q2->expr->eq( 'round(('.implode('+',$orderParts).')*1000)', $q2->bindValue( $colorMatchedTimes ) ).' AND '.$q2->expr->gt( 'lh_gallery_pallete_images.pid', $q2->bindValue( $pid ) ).')');
                       
               $q2->orderBy(implode('+',$orderParts).' ASC, lh_gallery_pallete_images.pid ASC');
              
               $q2->limit(5);
              
               $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
              
               $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                              
               
               // Right images search
               $q = $session->database->createSelectQuery();               
               $q->select( "*" )->from( "lh_gallery_images" );  
                
               $q2 = $q->subSelect();
               $q2->select( 'lh_gallery_pallete_images.pid' )->from( 'lh_gallery_pallete_images' );
                
               $conditions = array();
               $conditions[] = $q2->expr->eq( 'lh_gallery_pallete_images.pallete_id', $q2->bindValue($pallete_ids[0]) );
               for ($i = 1;$i < $count_palletes_ids; $i++) {
                   $q2->innerJoin( $q2->alias( 'lh_gallery_pallete_images', 'color_'.$i ), 'lh_gallery_pallete_images.pid', 'color_'.$i.'.pid' );
                   $conditions[] =  $q2->expr->eq( 'color_'.$i.'.pallete_id', $q2->bindValue($pallete_ids[$i]) );
               
               }  
               
               $q2->where(implode(' AND ',$conditions).' AND ('.$q2->expr->lt( 'round(('.implode('+',$orderParts).')*1000)', $q2->bindValue( $colorMatchedTimes ) ). ' OR '.$q2->expr->eq( 'round(('.implode('+',$orderParts).')*1000)', $q2->bindValue( $colorMatchedTimes ) ).' AND '.$q2->expr->lt( 'lh_gallery_pallete_images.pid', $q2->bindValue( $pid ) ).')');
                       
               $q2->orderBy(implode('+',$orderParts).' DESC, lh_gallery_pallete_images.pid DESC');
              
               $q2->limit(5);
              
               $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
              
               $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                              
               // For return to thumbnails page count  
               $q = $session->database->createSelectQuery();               
               $q->select( "count(lh_gallery_pallete_images.pid)" )->from( "lh_gallery_pallete_images" );  
                                               
               $conditions = array();
               $conditions[] = $q->expr->eq( 'lh_gallery_pallete_images.pallete_id', $q->bindValue($pallete_ids[0]) );
               for ($i = 1;$i < $count_palletes_ids; $i++) {
                   $q->innerJoin( $q->alias( 'lh_gallery_pallete_images', 'color_'.$i ), 'lh_gallery_pallete_images.pid', 'color_'.$i.'.pid' );
                   $conditions[] =  $q->expr->eq( 'color_'.$i.'.pallete_id', $q->bindValue($pallete_ids[$i]) );
               
               }  
               
               $q->where(implode(' AND ',$conditions).' AND ('.$q->expr->gt( 'round(('.implode('+',$orderParts).')*1000)', $q->bindValue( $colorMatchedTimes ) ). ' OR '.$q->expr->eq( 'round(('.implode('+',$orderParts).')*1000)', $q->bindValue( $colorMatchedTimes ) ).' AND '.$q->expr->gt( 'lh_gallery_pallete_images.pid', $q->bindValue( $pid ) ).')');
                   
               $q->limit(1);
               
               $stmt = $q->prepare();       
               $stmt->execute();
               $photos = $stmt->fetchColumn(); 
      
               $page = ceil(($photos+1)/20);
               
               return array('page' => $page,'imagesLeft' => $imagesLeft,'imagesRight' => $imagesRight);  
          } else {
                /**
                   * Let push maximum parformance from Mysql
                   * */
                $selectFields = array();
                $selectFields[] = 'LOG(`lh_gallery_pallete_images`.count)';
                $db = ezcDbInstance::get();   
                                      
                $innerJoins = array();
                $conditions[] = 'lh_gallery_pallete_images.pallete_id = '.(int)$pallete_ids[0];
                for ($i = 1;$i < $count_palletes_ids; $i++) {
                   $innerJoins[] = 'INNER JOIN `lh_gallery_pallete_images` AS '.'color_'.$i.' ON '.'color_'.$i.'.pid =  `lh_gallery_pallete_images`.pid';
                   $conditions[] =  'color_'.$i.'.pallete_id = '.(int)$pallete_ids[$i];
                   $selectFields[] = 'LOG(color_'.$i.'.count)';                   
                }
                                                
                // We create memory table and insert to it instantly
                $sql = "CREATE TEMPORARY TABLE color_search (
                pid INT( 10 ) UNSIGNED NOT NULL ,
                count INT( 6 ) UNSIGNED NOT NULL,
                INDEX USING BTREE (count,pid),
                INDEX USING BTREE (pid)
                ) ENGINE = MEMORY;
                INSERT INTO color_search (SELECT `lh_gallery_pallete_images`.pid,(".implode('+',$selectFields).")*1000 FROM `lh_gallery_pallete_images` ".implode(' ',$innerJoins)." WHERE ".implode(' AND ',$conditions).");";               
                $conditions = array(); 
                $stmt = $db->prepare($sql);
                $stmt->execute();
                
                // We fetch from memory table current image match status
                $session = erLhcoreClassGallery::getSession();
       
                $q = $session->database->createSelectQuery();
                 
                $q->select( "`count`" )->from( "color_search" ); 
                $q->where(
                    $q->expr->eq( 'pid', $q->bindValue($pid) ) 
                );
               
                $stmt = $q->prepare();       
                $stmt->execute();
                $colorMatchedTimes = $stmt->fetchColumn();
                               
                $db = ezcDbInstance::get(); 
                $session = erLhcoreClassGallery::getSession(); 
                    
                $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
                $q2 = $q->subSelect();
                $q2->select( 'pid' )->from( 'color_search' );
                           
                $q2->where( ' ('.$q2->expr->gt( 'count', $q2->bindValue( $colorMatchedTimes ) ). ' OR '.$q2->expr->eq( 'count', $q2->bindValue( $colorMatchedTimes ) ).' AND '.$q2->expr->gt( 'pid', $q2->bindValue( $pid ) ).')' )
                ->orderBy('count ASC, pid ASC')
                ->limit( 5 );
                
                $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
                
                $imagesLeft = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
               
                // Images right
               $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
               $q2 = $q->subSelect();
               $q2->select( 'pid' )->from( 'color_search' );    
                  
               $q2->where( '('.$q2->expr->lt( 'count', $q2->bindValue( $colorMatchedTimes ) ). ' OR '.$q2->expr->eq( 'count', $q2->bindValue( $colorMatchedTimes ) ).' AND '.$q2->expr->lt( 'pid', $q2->bindValue( $pid ) ).')' )
               ->orderBy('count DESC, pid DESC')
               ->limit( 5 );
                
               $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
               $imagesRight = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
                              
               $stmt = $db->prepare('SELECT count(pid) FROM color_search WHERE (count > :count OR count = :count AND pid > :pid) LIMIT 1');
               $stmt->bindValue( ':count',$colorMatchedTimes);            
               $stmt->bindValue( ':pid',$pid);
                     
               $stmt->execute();          
               $photos = $stmt->fetchColumn();
                    
               $page = ceil(($photos+1)/20);
               
               $stmt = $db->prepare('DROP TABLE color_search');
               $stmt->execute(); 
               
               return array('page' => $page,'imagesLeft' => $imagesLeft,'imagesRight' => $imagesRight); 
               
          }          
       } 
   }
   
   public static function getImages($paramsSearch = array())
   {       
       $paramsDefault = array('limit' => 100, 'offset' => 0);
       
       $params = array_merge($paramsDefault,$paramsSearch);
                 
       $conditions = array();
       $useMemoryTable = erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'memory_table');
       $count_palletes_ids = count($params['pallete_id']); 
       
        // Standard query if one color
       if ($count_palletes_ids == 1) { 
           
           $session = erLhcoreClassGallery::getSession();
           $q = $session->database->createSelectQuery();            
           $q->select( "*" )->from( "lh_gallery_images" );
            
           $q2 = $q->subSelect();
           $q2->select( 'lh_gallery_pallete_images.pid' )->from( 'lh_gallery_pallete_images' );               
           $params['filter']['pallete_id'] = $params['pallete_id'][0];   
           $q2->orderBy(isset($params['sort']) ? $params['sort'] : 'lh_gallery_pallete_images.count DESC, lh_gallery_pallete_images.pid DESC'); 
                  
       } elseif ($count_palletes_ids > 1) {
                               
          // Do not use memory table          
          if ($useMemoryTable == false) {
              
              $session = erLhcoreClassGallery::getSession();
              $q = $session->database->createSelectQuery();               
              $q->select( "*" )->from( "lh_gallery_images" );  
                
              $q2 = $q->subSelect();
              $q2->select( 'lh_gallery_pallete_images.pid' )->from( 'lh_gallery_pallete_images' ); 
              
              $orderParts = array();
              $conditions[] = $q2->expr->eq( 'lh_gallery_pallete_images.pallete_id', $q2->bindValue($params['pallete_id'][0]) );
              $orderParts[] = 'LOG(lh_gallery_pallete_images.count)';
              for ($i = 1;$i < $count_palletes_ids; $i++) {
                   $q2->innerJoin( $q2->alias( 'lh_gallery_pallete_images', 'color_'.$i ), 'lh_gallery_pallete_images.pid', 'color_'.$i.'.pid' );
                   $conditions[] =  $q2->expr->eq( 'color_'.$i.'.pallete_id', $q2->bindValue($params['pallete_id'][$i]) );
                   $orderParts[] = 'LOG(color_'.$i.'.count)';               
              }          
              $q2->orderBy(implode('+',$orderParts).' DESC, lh_gallery_pallete_images.pid DESC');
          } else { // Use memory table, the most efficient way to search by colors
                   
                $selectFields = array();
                $selectFields[] = 'LOG(`lh_gallery_pallete_images`.count)';
                $db = ezcDbInstance::get();   
                                      
                $innerJoins = array();
                $conditions[] = 'lh_gallery_pallete_images.pallete_id = '.(int)$params['pallete_id'][0];
                for ($i = 1;$i < $count_palletes_ids; $i++) {
                    
                   if (is_numeric($params['pallete_id'][$i]) && $params['pallete_id'][$i] > 0) {
                       $innerJoins[] = 'INNER JOIN `lh_gallery_pallete_images` AS '.'color_'.$i.' ON '.'color_'.$i.'.pid =  `lh_gallery_pallete_images`.pid';
                       $conditions[] =  'color_'.$i.'.pallete_id = '.(int)$params['pallete_id'][$i];
                   }
                   $selectFields[] = 'LOG(color_'.$i.'.count)';                   
                }
                                                
                // We create memory table and insert to it instantly
                $sql = "CREATE TEMPORARY TABLE color_search (
                pid INT( 10 ) UNSIGNED NOT NULL ,
                count INT( 6 ) UNSIGNED NOT NULL,
                INDEX USING BTREE (count,pid)
                ) ENGINE = MEMORY;
                INSERT INTO color_search (SELECT `lh_gallery_pallete_images`.pid,(".implode('+',$selectFields).")*1000 FROM `lh_gallery_pallete_images` ".implode(' ',$innerJoins)." WHERE ".implode(' AND ',$conditions).");";               
                $conditions = array(); 
                $stmt = $db->prepare($sql);
                $stmt->execute();
                   
                $sql = "SELECT * FROM `lh_gallery_images` INNER JOIN (SELECT pid FROM color_search ORDER BY color_search.count DESC, color_search.pid DESC LIMIT :offset,:limit) AS items ON items.pid = lh_gallery_images.pid";
                
                $stmt = $db->prepare($sql);                
                $stmt->bindValue( ':limit',$params['limit'],PDO::PARAM_INT);   
                $stmt->bindValue( ':offset',$params['offset'],PDO::PARAM_INT);
                          
                $stmt->execute();                
                $rows = $stmt->fetchAll( PDO::FETCH_ASSOC );
                  
                $stmt = $db->prepare("DROP TABLE `color_search`;");
                $stmt->execute();
                
                $result = array();
                             
                foreach ( $rows as $row )
                {
                    $object = new erLhcoreClassModelGalleryImage();
                    $object->setState(
                        $row
                    );                                        
                    $result[] = $object;
                }                

                return $result;               
          } 
       } 
            
       
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
      
      if (isset($params['filterlte']) && count($params['filterlte']) > 0)
      {
           foreach ($params['filterlte'] as $field => $fieldValue)
           {
               $conditions[] = $q2->expr->lte( $field, $q->bindValue($fieldValue) );
           } 
      }
      
      if (isset($params['filtergt']) && count($params['filtergt']) > 0)
      {
           foreach ($params['filtergt'] as $field => $fieldValue)
           {
               $conditions[] = $q2->expr->gt( $field,$q->bindValue( $fieldValue) );
           } 
      } 
      
      if (isset($params['filtergte']) && count($params['filtergte']) > 0)
      {
           foreach ($params['filtergte'] as $field => $fieldValue)
           {
               $conditions[] = $q2->expr->gte( $field,$q->bindValue( $fieldValue) );
           } 
      }      
       
      if (count($conditions) > 0)
      {
          $q2->where( 
                     $conditions   
          );
      }
      
      $q2->limit($params['limit'],$params['offset']);
            
      $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
            
      $objects = $session->find( $q, 'erLhcoreClassModelGalleryImage' );
      
      return $objects;      
   }
     
   public $id = null;
   public $red = null;
   public $green = null;
   public $blue = null;
   public $position = 0;

}


?>