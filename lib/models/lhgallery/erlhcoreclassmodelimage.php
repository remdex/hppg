<?

class erLhcoreClassModelGalleryImage {
      
   public function getState()
   {
       return array(
               'pid'            => $this->pid,
               'aid'            => $this->aid,             
               'filepath'       => $this->filepath,             
               'filename'       => $this->filename,             
               'filesize'       => $this->filesize,             
               'total_filesize' => $this->total_filesize,             
               'pwidth'         => $this->pwidth,             
               'pheight'        => $this->pheight,             
               'hits'           => $this->hits,             
               'ctime'          => $this->ctime,             
               'owner_id'       => $this->owner_id,             
               'pic_rating'     => $this->pic_rating,             
               'votes'          => $this->votes,             
               'title'          => $this->title,             
               'caption'        => $this->caption,             
               'keywords'       => $this->keywords,             
               'pic_raw_ip'     => $this->pic_raw_ip,             
               'approved'       => $this->approved,             
               'mtime'          => $this->mtime,             
               'comtime'        => $this->comtime,             
               'has_preview'    => $this->has_preview,             
               'anaglyph'       => $this->anaglyph,             
               'rtime'          => $this->rtime,
               'media_type'     => $this->media_type
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
       $Image = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryImage', (int)$pid );
       return $Image;
   }
   
   public function clearCache()
   {       
       $album = erLhcoreClassModelGalleryAlbum::fetch($this->aid);
       $album->clearAlbumCache();
   }
   
   public static function isImageOwner($pid, $skipChecking = false)
   {
       $image = erLhcoreClassModelGalleryImage::fetch($pid);
       
       if ($skipChecking==true) return $image;
       
       $currentUser = erLhcoreClassUser::instance();              
       if ($image->owner_id == $currentUser->getUserID()) return $image;        
       return false;  
   }
   
   public function removeThis(){
                 
       $photoPath = 'albums/'.$this->filepath;
       if (file_exists($photoPath.$this->filename))
            unlink($photoPath.$this->filename);
       
       if ($this->media_type == erLhcoreClassModelGalleryImage::mediaTypeIMAGE ) {
            
       	   if (file_exists($photoPath.'normal_'.$this->filename))
                unlink($photoPath.'normal_'.$this->filename); 
                     
           if (file_exists($photoPath.'thumb_'.$this->filename))
                unlink($photoPath.'thumb_'.$this->filename);                    
           	            
       } elseif ($this->media_type == erLhcoreClassModelGalleryImage::mediaTypeHTMLV ) {
                  
           if ($this->has_preview == 1) {      

              if (file_exists($photoPath.'normal_'.str_replace('.ogv','.jpg',$this->filename)))
                    unlink($photoPath.'normal_'.str_replace('.ogv','.jpg',$this->filename)); 
                         
             if (file_exists($photoPath.'thumb_'.str_replace('.ogv','.jpg',$this->filename)))
                    unlink($photoPath.'thumb_'.str_replace('.ogv','.jpg',$this->filename));                           
           }
       } elseif ($this->media_type == erLhcoreClassModelGalleryImage::mediaTypeVIDEO ) {
                  
           if (file_exists($photoPath.str_replace(array('.avi','.mpg','.wmv','.mpeg','.mp4'),'.flv',$this->filename)))
                    unlink($photoPath.str_replace(array('.avi','.mpg','.wmv','.mpeg','.mp4'),'.flv',$this->filename));
           
           if ($this->has_preview == 1) {      

              if (file_exists($photoPath.'normal_'.str_replace(array('.avi','.wmv','.mpg','.mpeg','.mp4'),'.jpg',$this->filename)))
                    unlink($photoPath.'normal_'.str_replace(array('.avi','.wmv','.mpg','.mpeg','.mp4'),'.jpg',$this->filename)); 
                         
             if (file_exists($photoPath.'thumb_'.str_replace(array('.avi','.wmv','.mpg','.mpeg','.mp4'),'.jpg',$this->filename)))
                    unlink($photoPath.'thumb_'.str_replace(array('.avi','.wmv','.mpg','.mpeg','.mp4'),'.jpg',$this->filename));                           
           }
           
       } elseif ($this->media_type == erLhcoreClassModelGalleryImage::mediaTypeSWF ) {
                  
           if ($this->has_preview == 1) {      
                       
             if (file_exists($photoPath.'thumb_'.str_replace('.swf','.jpg',$this->filename)))
                    unlink($photoPath.'thumb_'.str_replace('.swf','.jpg',$this->filename));                           
           }
       }
       
       $this->clearCache();
       erLhcoreClassModelGalleryDuplicateImage::deleteByPid($this->pid);
       erLhcoreClassModelGalleryMyfavoritesImage::deleteByPid($this->pid);
       erLhcoreClassModelGalleryPopular24::deleteByPid($this->pid);
       erLhcoreClassModelGalleryRated24::deleteByPid($this->pid);
       erLhcoreClassModelGalleryDuplicateImageHash::deleteByPid($this->pid);
       erLhcoreClassPalleteIndexImage::removeFromIndex($this->pid);
       erLhcoreClassModelGallerySphinxSearch::removeImage($this->pid);
       erLhcoreClassModelGalleryFaceData::removeImage($this->pid);
       
       erLhcoreClassGallery::getSession()->delete($this);
       
       // Expires last uploads shard index
	   erLhcoreClassGallery::expireShardIndexByIdentifier(array('last_uploads','last_commented'));
       
       CSCacheAPC::getMem()->increaseImageManipulationCache();
       
       // Update album last add time
       erLhcoreClassModelGalleryAlbum::updateAddTime(false,$this->aid);
   }
   
   public function __get($variable)
   {
       switch ($variable) {
        
           case 'name_user':
               
            if (trim($this->title) != '') {
                $this->name_user = trim($this->title);
                return $this->name_user;
            }  
               
            $string = $this->filename;               
        	$stringArr = explode('.',$string);        	
        	array_pop($stringArr);
            $string = implode(' ',$stringArr);
        	       	      		
        	$string = trim(preg_replace('#^\d+#', '',  $string));
        	$string=str_replace('_',' ',$string);	
        	$string=str_replace('-',' ',$string);
            $ArrTitle = explode(' ',$string);
            if (is_numeric($ArrTitle[sizeof($ArrTitle)-1]) && sizeof($ArrTitle) != 1) {array_pop($ArrTitle);
            $this->name_user = trim(strtolower(implode(' ',$ArrTitle)));
            return $this->name_user;
            }    
            
            $this->name_user = trim($string);
            return $this->name_user;
            break;                 
           		
       	case 'path':

       	    $cache = CSCacheAPC::getMem();
       	    $cacheKey = md5($cache->getCacheVersion('album_'.$this->aid).'album_path_'.$this->aid.'_siteaccess_'.erLhcoreClassSystem::instance()->SiteAccess);     
            if (($categoryPath = $cache->restore($cacheKey)) === false)
            {           	          	    
           	    $album = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryAlbum', $this->aid );       	     
           	    $categoryPath = $album->path;
           	    $cache->store($cacheKey,$categoryPath);
            }                   	  
           	$categoryPath[] = array('title' =>$this->name_user);           	
           	$this->path = $categoryPath;
       	    return $this->path;
       		break;        		  	
           	
       	case 'url_path':         
            if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'nice_url_enabled' ) == true)    
            {                         
                    $this->url_path = erLhcoreClassDesign::baseurl($this->nice_path_base,false);            
                    return $this->url_path;
            } else {
                $this->url_path = erLhcoreClassDesign::baseurl('gallery/image').'/'.$this->pid;
                return $this->url_path;
            }               
            break;
            
        case 'nice_path_base':
       	     $pathElements = array();
             foreach ($this->path as $item){
                $pathElements[] = urlencode(erLhcoreClassCharTransform::TransformToURL($item['title']));
             }
             $this->nice_path_base = implode('/',$pathElements).'-'.$this->pid.'p.html';
             return $this->nice_path_base;             
       	break;
       	    
       	case 'url_path_base':
            if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'nice_url_enabled' ) == true) { 
                    $this->url_path_base = erLhcoreClassDesign::baseurldirect($this->nice_path_base);
            } else {
                    $this->url_path_base = erLhcoreClassDesign::baseurldirect('gallery/image').'/'.$this->pid;
            } 
            return $this->url_path_base;           
            break;
            	     		
       	case 'filesize_user':        	    
       	    $this->filesize_user = round(($this->filesize/1024),2) . ' KB';
       	    return $this->filesize_user;
       		break;
       
       	case 'file_path_filesystem':       	  
       	        return 'albums/'.$this->filepath.$this->filename;
       	    break; 
       	         	
       	case 'album': 
       	    $this->album = erLhcoreClassModelGalleryAlbum::fetch($this->aid);
       	    return $this->album;
       		break;
       	        
       	case 'album_path':       	  
       	        $albumPath = $this->path;
       	        array_pop($albumPath);// Remove self element;
       	        $albumPath = array_pop($albumPath);
       	        $this->album_path = $albumPath['url'];
       	        $this->album_title = $albumPath['title']; // Cache for feature
       	        return $this->album_path;
       	    break;

       	case 'album_title':
           	    $albumPath = $this->path;
       	        array_pop($albumPath);// Remove self element;
       	        $albumPath = array_pop($albumPath);
       	        $this->album_path = $albumPath['url']; // Cache for feature
       	        $this->album_title = $albumPath['title'];
       	        return $this->album_title;
       	    break;    
       	      	   	
       	default:
       		break;
       }
   }
   
   
   
   public static function getImageCount($params = array())
   {
       if (!isset($params['disable_sql_cache']))
       {
          $sql = erLhcoreClassGallery::multi_implode(',',$params);  
                       
          $cache = CSCacheAPC::getMem();          
          $cacheKey = isset($params['cache_key']) ? md5($sql.$params['cache_key']) : md5('images_count_site_version_'.$cache->getCacheVersion('site_version').$sql);
          
          if (($result = $cache->restore($cacheKey)) !== false)
          {              
              return $result;
          }       
       }    
       
       $session = erLhcoreClassGallery::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(pid)" )->from( "lh_gallery_images" );     
       
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
        
      if (isset($params['use_index'])) {         
          $q->useIndex( $params['use_index'] );
      }

      $stmt = $q->prepare();       
      $stmt->execute();   
      $result = $stmt->fetchColumn(); 
      
      if (!isset($params['disable_sql_cache'])) {
              $cache->store($cacheKey,$result);           
      }                   
      
      return $result; 
   }
   
   public static function getImages($paramsSearch = array())
   {             
       $paramsDefault = array('limit' => 32, 'offset' => 0);       
       $params = array_merge($paramsDefault,$paramsSearch);
       
       if (!isset($params['disable_sql_cache']))
       {
          $sql = erLhcoreClassGallery::multi_implode(',',$params);  
                       
          $cache = CSCacheAPC::getMem();          
          $cacheKey = isset($params['cache_key']) ? md5($sql.$params['cache_key']) : md5('site_version_'.$cache->getCacheVersion('site_version').$sql);
          
          if (($result = $cache->restore($cacheKey)) !== false)
          {     
              return $result;
              
          }       
       }
       
       $session = erLhcoreClassGallery::getSession();
       $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );  
       
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
                  
                  if (isset($params['use_index'])) {         
                    $q->useIndex( $params['use_index'] );
                  }
                  
                  $q->limit($params['limit'],$params['offset']);
                            
                  $q->orderBy(isset($params['sort']) ? $params['sort'] : 'pid DESC' ); 
       } else {
           $q2 = $q->subSelect();
           $q2->select( 'pid' )->from( 'lh_gallery_images' );
                               
           if (isset($params['filter_shard']) && $params['filter_shard']['filter'] !== false) {
                $params = $params + $params['filter_shard']['filter']; 
                                
                if (isset($params['filter_shard']['filter']['shard_deduct_limit'])) {
                    $params['offset'] -=  $params['filter_shard']['filter']['shard_deduct_limit']; 
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
                   
          if (isset($params['use_index'])) {         
            $q2->useIndex( $params['use_index'] );
          }
          
          $q2->limit($params['limit'],$params['offset']);
          $q2->orderBy(isset($params['sort']) ? $params['sort'] : 'pid DESC');
                    
          $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );          
       }
       
      $objects = $session->find( $q );
            
      if (isset($params['filter_shard'])) {
          
          if ($params['filter_shard']['append_shard'] !== false){
              $currentImage = current($objects);
              $params['filter_shard']['append_shard']['pid'] = $currentImage->pid;
              erLhcoreClassGallery::addShardFilter($params['filter_shard']['append_shard']);
          }
      }
   
      
      if (!isset($params['disable_sql_cache']))
      {
              $cache->store($cacheKey,$objects);
      } 
         
      return $objects; 
   }
   
   /**
    * @param $imagesLeft Left images array
    * @param $imagesRight Right images array
    * 
    * @return array imagesLeft - Left images for ajax
    *               imagesRight - Right images for ajax
    *               hasLeft - indicates that we can show left navigation link
    *               hasRight - indicates that we can show right navigation link
    * 
    * */ 
   public static function getImagesSlices($imagesLeft, $imagesRight, $Image)
   {
       // Both sequances are full
       $hasLeft = false;
       $hasRight = false; 
       
       $hasPreviousImage = false; 
       $previousImage = null;
       
       $hasNextImage = false; 
       $nextImage = null; 
              
       $leftImagePID = null;
       $rightImagePID = null;
       
       $imagesAjax = array();
       
        if (count($imagesLeft) > 2 && count($imagesRight) > 2) {  
                       
            $hasLeft = true;
            $hasRight = true;  
                            
            $imagesLeft = array_slice($imagesLeft,0,2);
            $imagesRight = array_slice($imagesRight,0,2);
            
        } elseif (count($imagesLeft) == 1 && count($imagesRight) > 3) { 
            
            $hasLeft = false;
            $hasRight = true; 
                     
            $imagesRight = array_slice($imagesRight,0,3);
            
        } elseif (count($imagesRight) == 1 && count($imagesLeft) > 3) {    
                    
            $hasLeft = true;
            $hasRight = false;   
                     
            $imagesLeft = array_slice($imagesLeft,0,3);
            
        } elseif (count($imagesRight) == 2 && count($imagesLeft) > 2) {
            
            $hasRight = false; 
            $hasLeft = true;
            
            $imagesLeft = array_slice($imagesLeft,0,2);
            
        } elseif (count($imagesLeft) == 2 && count($imagesRight) > 2) {
            
            $hasRight = true; 
            $hasLeft = false;
           
            $imagesRight = array_slice($imagesRight,0,2);
            
        } elseif (count($imagesLeft) == 0 && count($imagesRight) > 4) {
            
            $hasRight = true; 
            $hasLeft = false;
           
            $imagesRight = array_slice($imagesRight,0,4);
            
        } elseif (count($imagesRight) == 0 && count($imagesLeft) > 4) {
            
            $hasRight = false; 
            $hasLeft = true;
           
            $imagesLeft = array_slice($imagesLeft,0,4);
        }
        
        if (count($imagesRight) > 0) { 
            $hasNextImage = true;
            $nextImage = current($imagesRight);
        }
        
        if (count($imagesLeft) > 0) { 
            $hasPreviousImage = true;
            $previousImage = current($imagesLeft);
        }

        $imagesAjax = array_merge(array_reverse((array)$imagesLeft),array($Image->pid => $Image),(array)$imagesRight);
        
        if ($hasLeft == true) {
            reset($imagesAjax);
            $lastImages = current($imagesAjax);
            $leftImagePID = $lastImages->pid;
        }       
        
        if ($hasRight == true) {                        
            end($imagesAjax);
            $lastImages = current($imagesAjax);
            $rightImagePID = $lastImages->pid;    
        }
        
        return array (            
            'imagesAjax'        => $imagesAjax,
            'hasLeft'           => $hasLeft,       
            'hasRight'          => $hasRight, 
                  
            'leftImagePID'      => $leftImagePID,
            'rightImagePID'     => $rightImagePID,
            
            'hasPreviousImage'  => $hasPreviousImage,       
            'hasNextImage'      => $hasNextImage, 
                  
            'nextImage'         => $nextImage,       
            'prevImage'         => $previousImage,       
        );
   }
    
   public $pid = null;
   public $aid = '';
   public $filepath = '';
   public $filename = '';
   public $filesize = '';
   public $total_filesize = '';
   public $pwidth = '';
   public $pheight = '';
   public $hits = '';
   public $ctime = '';
   public $owner_id = '';
   public $pic_rating = '';
   public $votes = '';
   public $title = '';
   public $caption = '';
   public $keywords = '';
   public $approved = 0;
   public $pic_raw_ip = '';
   public $mtime = 0;
   public $comtime = 0;
   public $has_preview = 0;
   public $anaglyph = 0;
   public $rtime = 0;
   public $media_type = 0;
   
   const mediaTypeIMAGE = 0;
   const mediaTypeSWF   = 1;
   const mediaTypeHTMLV = 2;
   const mediaTypeHTMLA = 3;
   const mediaTypeFLV   = 4;
   const mediaTypeVIDEO = 5;
   
}


?>