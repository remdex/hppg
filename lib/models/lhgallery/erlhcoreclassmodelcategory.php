<?

class erLhcoreClassModelGalleryCategory {
        
    public function getState()
   {
       return array(
               'cid'         => $this->cid,
               'owner_id'    => $this->owner_id,             
               'name'        => $this->name,             
               'description' => $this->description,             
               'pos'         => $this->pos,             
               'parent'      => $this->parent,             
               'has_albums'  => $this->has_albums,             
               'hide_frontpage' => $this->hide_frontpage           
       );
   }
   
   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   }
   
   public static function fetch($cid)
   {
       $Category = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryCategory', (int)$cid );
       return $Category;
   }
   
   
   public static function isCategoryOwner($aid,$skipChecking = false)
   {
       $category = erLhcoreClassModelGalleryCategory::fetch($aid);
                   
       if ($skipChecking==true) return $category;
         
       
         
       $currentUser = erLhcoreClassUser::instance();              
       if ($category->owner_id == $currentUser->getUserID()) return $category;
        
       return false;  
   }
   
    
   public function clearCategoryCache()
   {
       $cache = CSCacheAPC::getMem();
       $cache->increaseCacheVersion('category_'.$this->cid); 
       $cache->increaseCacheVersion('category_0'); 
       $cache->increaseCacheVersion('site_version');
             
       $pathObjects = array();       
       erLhcoreClassModelGalleryCategory::calculatePathObjects($pathObjects,$this->cid);
       foreach ($pathObjects as $category)
       {
            $cache->increaseCacheVersion('category_'.$category->cid);          
       }       
   }
    
   public static function getParentCategories($paramsSearch = array())
   {       
        $paramsDefault = array('limit' => 8, 'offset' => 0);
        
        $params = array_merge($paramsDefault,$paramsSearch);
        
        $conditions = array();
        
        $session = erLhcoreClassGallery::getSession();
        $q = $session->createFindQuery( 'erLhcoreClassModelGalleryCategory' ); 
           
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
                
        $q->orderBy(isset($params['sort']) ? $params['sort'] : 'pos' ); 
      
        if (!isset($params['disable_sql_cache']))
        {
           $cache = CSCacheAPC::getMem();  
           $sql = erLhcoreClassGallery::multi_implode(',',$params); 
          
           $cacheKey = isset($params['cache_key']) ? md5($sql.$params['cache_key']) : md5('site_version_'.$cache->getCacheVersion('site_version').$sql);      
             
           if (($objects = $cache->restore($cacheKey)) === false)
           {
               $objects = $session->find( $q ); 
               $cache->store($cacheKey,$objects);
           } 
           
        } else {
            
            if (!isset($params['use_iterator'])){
                $objects = $session->find( $q ); 
            } else {
                $objects = $session->findIterator( $q );
            }
        
        }
        
        return $objects; 
   }
   
   public function removeThis()
   {
       $albums = erLhcoreClassGallery::getSession()->getRelatedObjects( $this, "erLhcoreClassModelGalleryAlbum" );
       foreach ($albums as $album) 
       {
           $album->removeThis();
       }
        
       $parentCategorys = erLhcoreClassGallery::getSession()->getRelatedObjects( $this, "erLhcoreClassModelGalleryCategory" ); 
       foreach ($parentCategorys as $category) 
       {
           $category->removeThis();
       } 
       
       $this->clearCategoryCache();
                   
       erLhcoreClassGallery::getSession()->delete($this);        
       CSCacheAPC::getMem()->increaseImageManipulationCache();
   }
   
   
   
   public static function fetchCategoryColumn($params = array(),$column = 'COUNT(cid)')
   {
       $session = erLhcoreClassGallery::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( $column )->from( "lh_gallery_categorys" );     
         
       $conditions = array();
       
       if (isset($params['filter']) && count($params['filter']) > 0)
       {
           foreach ($params['filter'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->eq( $field, $q->bindValue( $fieldValue ) );
           } 
      }  
      
      if (isset($params['filterin']) && count($params['filterin']) > 0)
       {
           foreach ($params['filterin'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->in( $field,   $fieldValue );
           } 
      }     
       
      if (isset($params['filterlt']) && count($params['filterlt']) > 0)
       {
           foreach ($params['filterlt'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->lt( $field, $q->bindValue( $fieldValue ) );
           } 
      }
      
      if (isset($params['filtergt']) && count($params['filtergt']) > 0)
       {
           foreach ($params['filtergt'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->gt( $field, $q->bindValue( $fieldValue ) );
           } 
      }
      
      if (count($conditions) > 0)
      {
          $q->where( 
                     $conditions   
          );
      }         
      
      if (!isset($params['disable_sql_cache']))
      {
          $sql = erLhcoreClassGallery::multi_implode(',',$params);   
          $cache = CSCacheAPC::getMem();          
          $cacheKey = isset($params['cache_key']) ? md5($column.$sql.$params['cache_key']) : md5('site_version_'.$cache->getCacheVersion('site_version').$sql.$column);
                             
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
        
   
   public static function getCategoryPath(& $array,$category_id)
   {     
      $cache = CSCacheAPC::getMem();
      $cacheKey = md5('version_'.$cache->getCacheVersion('category_'.$category_id).'category_path_'.$category_id.'_siteaccess_'.erLhcoreClassSystem::instance()->SiteAccess);
      
      if (($path = $cache->restore($cacheKey)) === false) {
         erLhcoreClassModelGalleryCategory::calculatePath($array,$category_id);
         $cache->store($cacheKey,$array);
      } else {
         $array = $path;        
      } 
   }
   
   public static function getCategoryPathURL(& $array,$category_id)
   {     
      $cache = CSCacheAPC::getMem();     
      if (($path = $cache->restore(md5('version_'.$cache->getCacheVersion('category_'.$category_id).'category_path_url'.$category_id))) === false)
      {     
         erLhcoreClassModelGalleryCategory::calculatePathURL($array,$category_id);
         $cache->store(md5('version_'.$cache->getCacheVersion('category_'.$category_id).'category_path_url'.$category_id),$array);
      } else {
         $array = $path;        
      } 
   }
   
   public static function calculatePath(& $array,$category_id){
       static $recursionProtect = 0;
       
       $category = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryCategory', (int)$category_id );
       
       $array[] = array('url' => $category->path_url,'title' => $category->name);   
           
       if ($category->parent != 0){
          erLhcoreClassModelGalleryCategory::calculatePath($array,$category->parent); 
       } else {
          $array = array_reverse($array); 
       }
       $recursionProtect++; 
       
       if ($recursionProtect > 500) exit;
   }

   public static function calculatePathObjects(& $array,$category_id){
       static $recursionProtect = 0;
       
       $category = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryCategory', (int)$category_id );
       
       $array[] = $category;   
           
       if ($category->parent != 0){
          erLhcoreClassModelGalleryCategory::calculatePathObjects($array,$category->parent); 
       } else {
          $array = array_reverse($array); 
       }
       $recursionProtect++; 
       
       if ($recursionProtect > 500) exit;
   } 
   
   public static function calculatePathURL(& $array,$category_id){
       static $recursionProtect = 0;
       
       $category = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryCategory', (int)$category_id );
       
       $array[] = array('title' => $category->name);   
           
       if ($category->parent != 0){
          erLhcoreClassModelGalleryCategory::calculatePathURL($array,$category->parent); 
       } else {
          $array = array_reverse($array); 
       }
       $recursionProtect++; 
       
       if ($recursionProtect > 500) exit;
   }
   
   public function __get($variable)
   {
       switch ($variable) {
       	case 'albums_count':       	    
       	    $albums = 0;
       	    foreach (erLhcoreClassModelGalleryCategory::getParentCategories(array('filter' => array('parent' => $this->cid),'disable_sql_cache' => true,'use_iterator' => true,'limit' => 1000000)) as $category)
       	    {
       	        $albums += erLhcoreClassModelGalleryAlbum::getAlbumCount(array('cache_key' => CSCacheAPC::getMem()->getCacheVersion('category_'.$category->cid),'filter' => array('category' => $category->cid)));
       	        
       	    }
       	    $albums += erLhcoreClassModelGalleryAlbum::getAlbumCount(array('cache_key' => CSCacheAPC::getMem()->getCacheVersion('category_'.$this->cid),'filter' => array('category' => $this->cid)));
       	    
       		$this->albums_count = $albums;
       		return  $this->albums_count;
       		break;
       	
       	case 'path_url':
            
            if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'nice_url_enabled' ) == true)    
            {    
                    $pathElements = array();                
                    $arrayPath = array();
                    erLhcoreClassModelGalleryCategory::getCategoryPathURL($arrayPath,$this->cid);
                    foreach ($arrayPath as $item){
                        $pathElements[] = erLhcoreClassCharTransform::TransformToURL($item['title']);
                    }               
                    $this->path_url = erLhcoreClassDesign::baseurl(implode('/',$pathElements).'-'.$this->cid.'c.html',false);    
                    return $this->path_url;
            } else {
                return erLhcoreClassDesign::baseurl('gallery/category').'/'.$this->cid;
            } 
            break;
       	      	
       	case 'owner':
       	    $this->owner = false;
       	    try{
	       		if ($this->owner_id > 0){
	       	   		$this->owner = erLhcoreClassModelUser::fetch($this->owner_id);
	       		}
       	    } catch (Exception $e) {
       	    	// Nothing to do
       	    }
       	    
       		return $this->owner;
       	    break;	
       			
       	case 'images_count':       	    
       	    $imagesCount = 0;
       	    foreach (erLhcoreClassModelGalleryCategory::getParentCategories(array('filter' => array('parent' => $this->cid),'disable_sql_cache' => true,'use_iterator' => true,'limit' => 1000000)) as $category)
       	    {
       	        $albums = erLhcoreClassModelGalleryAlbum::getAlbumsIDByFilter(array('filter' => array('category' => $category->cid)));
       	        if (is_array($albums) && count($albums) > 0){
       	            $imagesCount += erLhcoreClassModelGalleryImage::getImageCount(array('cache_key' => CSCacheAPC::getMem()->getCacheVersion('category_'.$this->cid),'filter' => array('approved' => 1),'filterin' => array('aid' => $albums)));
       	        }
       	    }       	    
       	    $albums = erLhcoreClassModelGalleryAlbum::getAlbumsIDByFilter(array('filter' => array('category' => $this->cid)));
       	           	    
       	    $imagesAppend = 0;
       	    if (is_array($albums) && count($albums) > 0)
       	    $imagesAppend = erLhcoreClassModelGalleryImage::getImageCount(array('cache_key' => 'category_'.CSCacheAPC::getMem()->getCacheVersion('category_'.$this->cid),'filter' => array('approved' => 1), 'filterin' => array('aid' => $albums)));
       	           	    
       	    $this->images_count = $imagesAppend+$imagesCount;      	    
       	    return $this->images_count;
       	    
       		break;
       
       	default:
       		break;
       }
   }
   
   public $cid = null;
   public $owner_id = '';
   public $name = '';
   public $description = '';
   public $pos = '';
   public $parent = '';
   public $hide_frontpage = 0;
   public $has_albums = 0;

}


?>