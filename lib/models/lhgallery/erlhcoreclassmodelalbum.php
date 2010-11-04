<?

class erLhcoreClassModelGalleryAlbum {
        
    public function getState()
   {
       return array(
               'aid'         => $this->aid,
               'title'       => $this->title,             
               'description' => $this->description,             
               'pos'         => $this->pos,             
               'category'    => $this->category,             
               'keyword'     => $this->keyword,             
               'owner_id'    => $this->owner_id,             
               'public'      => $this->public,             
       );
   }
   
   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   }
   
   public static function fetch($aid)
   {
       try {
        $Album = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryAlbum', (int)$aid );
       } catch (Exception $e){
        erLhcoreClassModule::redirect('/');
        exit;
       }
       return $Album;
   } 
   
   public static function isAlbumOwner($aid,$skipChecking = false)
   {
       $album = erLhcoreClassModelGalleryAlbum::fetch($aid);
       
        if ($skipChecking==true) return $album;
       
       $currentUser = erLhcoreClassUser::instance();              
       if ($album->owner_id == $currentUser->getUserID()) return $album;
        
       return false;  
   }
   
   public static function canUpload($aid,$skipChecking = false)
   {
       $album = erLhcoreClassModelGalleryAlbum::fetch($aid);
       
       if ($skipChecking==true) return $album;
       
       $currentUser = erLhcoreClassUser::instance();              
       if ($album->public == 1 || ($currentUser->isLogged() && $album->owner_id == $currentUser->getUserID()) ) return $album;
        
       return false;  
   }
   
   
   public function removeThis()
   {
       $images = erLhcoreClassGallery::getSession()->getRelatedObjects( $this, "erLhcoreClassModelGalleryImage" );       
       foreach ($images as $image) 
       {
           $image->removeThis();
       }
         
       $photoPath = 'albums/userpics/'.$this->owner_id.'/'. $this->aid;
       
       if (file_exists($photoPath))
            @rmdir($photoPath);
                         
       $this->clearAlbumCache(); 
       erLhcoreClassGallery::getSession()->delete($this); 
       
       CSCacheAPC::getMem()->increaseImageManipulationCache();
   }
   
   public function storeThis()
   {       
       erLhcoreClassGallery::getSession()->save($this); 
       $this->clearAlbumCache();
   }
   
   public function updateThis()
   {
       erLhcoreClassGallery::getSession()->update($this);         
       $this->clearAlbumCache();        
   }
   
   // Album cache clear
   public function clearAlbumCache()
   {
       // Clear album cache
       $cache = CSCacheAPC::getMem(); 
       $cache->increaseCacheVersion('album_'.$this->aid);
       $cache->increaseCacheVersion('site_version');
       
       $category = erLhcoreClassModelGalleryCategory::fetch($this->category);
       $category->clearCategoryCache();
   }
   
   public function __get($variable)
   {
       switch ($variable) {
       	case 'images_count':
       	       $this->images_count = erLhcoreClassModelGalleryImage::getImageCount(array('cache_key' => CSCacheAPC::getMem()->getCacheVersion('album_'.$this->aid),'filter' => array('aid' => $this->aid,'approved' => 1)));
       		   return $this->images_count;
       		break;
       		
       	case 'path':        	    
           	    $categoryPath = array();
           	    erLhcoreClassModelGalleryCategory::getCategoryPath($categoryPath,$this->category); 
           	    $categoryPath[] = array('title' =>$this->title,'url' => $this->url_path); 
           	    $this->path = $categoryPath;         	    
           	    return $this->path;
       		break;
       		
       		case 'path_album':        	    
           	    $categoryPath = array();
           	    erLhcoreClassModelGalleryCategory::getCategoryPath($categoryPath,$this->category); 
           	    $categoryPath[] = array('title' =>$this->title);  
           	    $this->path_album = $categoryPath;         	    
           	    return $this->path_album;
       		break;
       		       		       		
       	case 'url_path':       	
       	    if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'nice_url_enabled' ) == true)    
       	    {    
           	    $pathElements = array();
           	    foreach ($this->path_album as $item){
           	        $pathElements[] = erLhcoreClassCharTransform::TransformToURL($item['title']);
           	    }      
           	    $this->url_path	= erLhcoreClassDesign::baseurl(implode('/',$pathElements).'-'.$this->aid.'a.html'); 
           	    return $this->url_path;
       	    } else {       	        
       	        return erLhcoreClassDesign::baseurl('/gallery/album/'.$this->aid);
       	    }      	    
       	    break;
       	    	
        case 'album_thumb_path':
                // FIX me, we show only approved photo, but we should check if user has access to see all images.        	    
           	    $images = erLhcoreClassModelGalleryImage::getImages(array('cache_key' => CSCacheAPC::getMem()->getCacheVersion('album_'.$this->aid),'filter' => array('aid' => $this->aid,'approved' => 1),'limit' => 1));
           	    foreach ($images as $image)
           	    {
           	        if ($image->media_type == erLhcoreClassModelGalleryImage::mediaTypeIMAGE ) {
           	            return $image->filepath.'thumb_'.urlencode($image->filename);
           	        } elseif ($image->media_type == erLhcoreClassModelGalleryImage::mediaTypeHTMLV ) {   
           	                  
           	           if ($image->has_preview == 1) {           	               
                            return $image->filepath.'thumb_'.urlencode(str_replace('.ogv','.jpg',$image->filename));
           	           }
           	        }
           	    }        
           	    return false;   	    
       		break;
       				
             
       	default:
       		break;
       }
   }
      
   public static function getAlbumsIDByFilter($params)
   {
       
       $session = erLhcoreClassGallery::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "aid" )->from( "lh_gallery_albums" );     
         
       if (isset($params['filter']) && count($params['filter']) > 0)
       {
           $conditions = array();
           
           foreach ($params['filter'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->eq( $field, $q->bindValue( $fieldValue ) );
           }
           
           $q->where( 
                 $conditions   
           );
      }  
      
      $sql = erLhcoreClassGallery::multi_implode(',',$params);    
      $cache = CSCacheAPC::getMem();      
      if (($result = $cache->restore(md5('site_version_'.$cache->getCacheVersion('site_version').$sql))) === false)
      {
          $stmt = $q->prepare();       
          $stmt->execute();   
          $result = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);            
          $cache->store(md5('site_version_'.$cache->getCacheVersion('site_version').$sql),$result);
      }
      
      return $result;
   }
   
   public static function getAlbumCount($params = array())
   {
       $session = erLhcoreClassGallery::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(aid)" )->from( "lh_gallery_albums" );   
         
       if (isset($params['filter']) && count($params['filter']) > 0)
       {
           $conditions = array();
           
           foreach ($params['filter'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->eq( $field, $q->bindValue($fieldValue ));
           }
           
           $q->where( 
                 $conditions   
           );
      }  
       
      if (!isset($params['disable_sql_cache']))
      {
          $sql = erLhcoreClassGallery::multi_implode(',',$params);
                  
          $cache = CSCacheAPC::getMem(); 
          $cacheKey = isset($params['cache_key']) ? md5($sql.$params['cache_key']) : md5('site_version_'.$cache->getCacheVersion('site_version').$sql);  

                   
          if (($result = $cache->restore($cacheKey)) === false)
          {
              $stmt = $q->prepare();       
              $stmt->execute();   
              $result = $stmt->fetchColumn();            
              $cache->store($cacheKey,$result);
          }      
      } else {
          $stmt = $q->prepare();       
          $stmt->execute();  
          $result = $stmt->fetchColumn(); 
      }
      
      return $result; 
   }
   
   public static function getAlbumsByCategory($paramsSearch = array())
   {
       $paramsDefault = array('limit' => 8, 'offset' => 0);
       
       $params = array_merge($paramsDefault,$paramsSearch);
       
       $session = erLhcoreClassGallery::getSession();
       $q = $session->createFindQuery( 'erLhcoreClassModelGalleryAlbum' );  
              
       if (isset($params['filter']) && count($params['filter']) > 0)
       {
           $conditions = array();
           
           foreach ($params['filter'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->eq( $field,$q->bindValue( $fieldValue ));
           }
           
           $q->where( 
                 $conditions   
           );
      } 
      
      $q->limit($params['limit'],$params['offset']);
          
      $q->orderBy( isset($paramsSearch['sort']) ? $paramsSearch['sort'] : 'pos DESC' ); 
              
      $objects = $session->find( $q );         
      return $objects; 
   }
     
   public $aid = null;
   public $title = '';
   public $description = '';
   public $pos = '';
   public $category = '';
   public $keyword = '';
   public $owner_id = 0;
   public $public = 0;

}


?>