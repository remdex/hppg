<?php

class erLhcoreClassModelGalleryFaceData {
        
   public function getState()
   {
       return array(
               'pid'         => $this->pid,
               'data'        => $this->data,
               'sphinx_data' => $this->sphinx_data,
       );
   }

   public static function fetch($pid)
   {
       $pallete = erLhcoreClassGallery::getSession('slave')->load( 'erLhcoreClassModelGalleryFaceData', (int)$pid );
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
       $session = erLhcoreClassGallery::getSession('slave');
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_gallery_face_data" );     
         
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
        
   public static function getList($paramsSearch = array())
   {
       $paramsDefault = array('limit' => 500, 'offset' => 0);
       
       $params = array_merge($paramsDefault,$paramsSearch);
       
       $session = erLhcoreClassGallery::getSession('slave');
       $q = $session->createFindQuery( 'erLhcoreClassModelGalleryFaceData' );  
       
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
      $q->orderBy(isset($params['sort']) ? $params['sort'] : 'id DESC' ); 
       
      $objects = $session->find( $q );
                           
      return $objects; 
   }

   public static function removeImage($pid) {
       
       try {           
           $imageIndex = self::fetch($pid);
           $imageIndex->removeThis();           
       } catch (Exception $e) {
           
       }
       
   }
   
   public function removeThis()
   {
       erLhcoreClassGallery::getSession()->delete($this);
   }
   
   // Used only in cronjob
   public static function indexUnindexedImages($limit = 32)
   {           
        if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'face_search', 'enabled' ) == true)
        {            
            if (($lastIndex = erLhcoreClassPalleteIndexImage::getLastIndex('face_index')) == 0)
            {
                $db = ezcDbInstance::get('slave');
                $stmt = $db->prepare("SELECT MAX(pid) as last_index_image FROM lh_gallery_face_data");
                $stmt->execute();
                $lastIndex = (int)$stmt->fetchColumn(); 
            }

            $imagesUnindexed = erLhcoreClassModelGalleryImage::getImages(array('limit' => $limit, 'sort' =>  'pid ASC','filtergt' => array('pid' => $lastIndex)));
            $lastIndexNew = $lastIndex;
            foreach ($imagesUnindexed as $image)
            {
                echo "Indexing face image PID - ",$image->pid,"\n";
                self::indexImage($image);
                
                $lastIndexNew = $image->pid;
            }

            // Changed something
            if ($lastIndexNew != $lastIndex) {
                echo "Updating last indexed face PID - ",$lastIndexNew,"\n";
                erLhcoreClassPalleteIndexImage::setLastIndex('face_index',$lastIndexNew);
            }
        }
   }
   
   static function getFaceRestClientInstance() {
        if (self::$faceRestClient == NULL) {
            self::$faceRestClient = new FaceRestClient(erConfigClassLhConfig::getInstance()->conf->getSetting( 'face_search', 'api_key' ), erConfigClassLhConfig::getInstance()->conf->getSetting( 'face_search', 'api_secret' ));
        }
        return self::$faceRestClient;
   }
   
   /**
    * We cannot use __set options for image album because of findIterator, it does not reset's album internal variable.
    * 
    * */
   public static function indexImage($image,$checkDelay = false) {

       if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'face_search', 'enabled' ) == true && $image->approved == 1 && ($checkDelay == false || ($checkDelay == true && erConfigClassLhConfig::getInstance()->conf->getSetting( 'face_search', 'delay_index' ) == false))) {

           if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'face_search', 'use_full_size') === true) {
               $photoPath = 'albums/'.$image->filepath. $image->filename;    
           } else {
               $photoPath = 'albums/'.$image->filepath.'normal_'. $image->filename;
           }

           if ( ((erConfigClassLhConfig::getInstance()->conf->getSetting('site','file_storage_backend') == 'filesystem' && file_exists($photoPath) && is_file($photoPath)) || erConfigClassLhConfig::getInstance()->conf->getSetting('site','file_storage_backend') == 'amazons3')&& $image->media_type == erLhcoreClassModelGalleryImage::mediaTypeIMAGE ) { 

               if ( erConfigClassLhConfig::getInstance()->conf->getSetting('site','file_storage_backend') == 'filesystem' ) {
                    $url = 'http://' . erConfigClassLhConfig::getInstance()->conf->getSetting( 'site','site_domain') . '/' . $photoPath;
               } else {
                   if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'face_search', 'use_full_size') === true) {
                       $url = erConfigClassLhConfig::getInstance()->conf->getSetting('amazons3','endpoint') . '/albums/'.$image->filepath.$image->filename;
                   } else {
                       $url = erConfigClassLhConfig::getInstance()->conf->getSetting('amazons3','endpoint') . '/albums/'.$image->filepath.'normal_'.$image->filename;
                   }
               }
           
               
               $response = self::getFaceRestClientInstance()->faces_detect($url);           
               $tagsData = array();
               
               if (!empty($response->photos)) {
                   
                   // Tagging formula for gender,glasses,smiling attributes
                   $max = 100;
                   $min = 1;
                   $rmax = 10;
                   $rmin = 1;
                                    
                   foreach ($response->photos as $photo_data)
                   {
                       if (!empty($photo_data->tags)) {
                           foreach ($photo_data->tags as $tagData) {
                               
                               foreach ($tagData->attributes as $attribute => $data){
                                   switch ($attribute) {
                                   	case 'face':
                                   		   $tagsData[] = 'face';
                                   		break;
            
                                   	case 'gender':                       	       
                                   	       // Similarity
                                   		   $tagsData[] = trim(str_repeat(' '.$data->value,round((($rmin*($data->confidence-$min))/($max-$min))*5)));                       		   
                                   		break;
                                   		
                                   	case 'mood':
                                   	       // Similarity
                                   		   $tagsData[] = trim(str_repeat(' '.$data->value,round((($rmin*($data->confidence-$min))/($max-$min))*5)));                       		   
                                   		break;
                                   			
                                   	case 'smiling':
                                   	       // Similarity
                                   	       if ($data->value == 'true') {                                    	                              	       
                                   		       $tagsData[] = trim(str_repeat(' smiling',round((($rmin*($data->confidence-$min))/($max-$min))*5)));    
                                   	       }                   		   
                                   		break;
                                   				
                                   	case 'glasses':
                                   	       // Similarity
                                   	       if ($data->value == 'true') {                       	                              	       
                                   		       $tagsData[] = trim(str_repeat(' glasses',round((($rmin*($data->confidence-$min))/($max-$min))*5)));  
                                   	       }                     		   
                                   		break;
                                   
                                   	default:
                                   		break;
                                   }
                               }                   
                           }
                       }
                   }
        
                   $db = ezcDbInstance::get(); 
                   $stmt = $db->prepare('REPLACE INTO lh_gallery_face_data VALUES (:pid,:data,:sphinx_data)');
                   $stmt->bindValue(':pid',$image->pid);
                   $stmt->bindValue(':data',serialize($response));
                   $stmt->bindValue(':sphinx_data',implode(' ',$tagsData));               
                   $stmt->execute();
               }
               // Avoid to many requests at the same time
               usleep(erConfigClassLhConfig::getInstance()->conf->getSetting( 'face_search', 'request_delay' ));
         }         
            
       } elseif ($image->approved == 0) {
           self::removeImage($image->pid);
       }
   }
   
   public $pid = null;
   public $data = '';   
   public $sphinx_data = '';
   
   static private $faceRestClient = NULL;

}


?>