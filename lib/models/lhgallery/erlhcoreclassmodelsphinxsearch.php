<?php

class erLhcoreClassModelGallerySphinxSearch {
        
   public function getState()
   {
       return array(
               'id'          => $this->id,
               'title'       => $this->title,             
               'hits'        => $this->hits,             
               'caption'     => $this->caption,             
               'filename'    => $this->filename,            
               'file_path'   => $this->file_path,           
               'mtime'       => $this->mtime,           
               'comtime'     => $this->comtime,           
               'rtime'       => $this->rtime,           
               'pic_rating'  => $this->pic_rating,           
               'votes'       => $this->votes,           
               'pwidth'      => $this->pwidth,           
               'pheight'     => $this->pheight,           
               'colors'      => $this->colors,           
               'text_index'  => $this->text_index,
               'pid'         => $this->pid,
       );
   }
   
   public static function fetch($pid)
   {
       $pallete = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGallerySphinxSearch', (int)$pid );
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
       $q->select( "COUNT(id)" )->from( "lh_gallery_sphinx_search" );     
         
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
       
       $session = erLhcoreClassGallery::getSession();
       $q = $session->createFindQuery( 'erLhcoreClassModelGallerySphinxSearch' );  
       
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
   public static function indexUnindexedImages()
   {           
        if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'sphinx', 'enabled' ) == true)
        {            
            if (($lastIndex = erLhcoreClassPalleteIndexImage::getLastIndex('sphinx_index')) == 0)
            {
                $db = ezcDbInstance::get(); 
                $stmt = $db->prepare("SELECT MAX(pid) as last_index_image FROM lh_gallery_sphinx_search");
                $stmt->execute();
                $lastIndex = (int)$stmt->fetchColumn(); 
            }

            $imagesUnindexed = erLhcoreClassModelGalleryImage::getImages(array('sort' =>  'pid ASC','filtergt' => array('pid' => $lastIndex)));
            $lastIndexNew = $lastIndex;
            foreach ($imagesUnindexed as $image)
            {
                echo "Indexing sphinx image PID - ",$image->pid,"\n";
                self::indexImage($image);
                
                $lastIndexNew = $image->pid;
            }

            // Changed something
            if ($lastIndexNew != $lastIndex) {
                echo "Updating last indexed sphinx PID - ",$lastIndexNew,"\n";
                erLhcoreClassPalleteIndexImage::setLastIndex('sphinx_index',$lastIndexNew);
            }
        }
   }
   
    
   // Updates only desirable attributes
   public static function indexAttributes($image, $attributes){

       $db = ezcDbInstance::get();

       $sqlUpdate = array();
       foreach (array_keys($attributes) as $field) {
           $sqlUpdate[] = "$field = :$field";
       }

       $stmt = $db->prepare('UPDATE `lh_gallery_sphinx_search` SET '.implode(',',$sqlUpdate).' WHERE id = :id LIMIT 1');
       $stmt->bindValue( ':id',$image->pid);

       foreach ($attributes as $field => $objectAttribute){
            $stmt->bindValue( ':'.$field,$image->{$objectAttribute});
       }    

       $stmt->execute(); 
   }
   
   public static function updateColorAttribute($image) {
           
       $session = erLhcoreClassGallery::getSession();

       $q = $session->database->createSelectQuery( );
       $q->select( 'pallete_id,count' )->from( 'lh_gallery_pallete_images' );
       $q->where(
       $q->expr->eq( 'pid', $q->bindValue($image->pid) )
       );

       // We use only first 40 records
       $q->limit(40,0);
       $q->orderBy('count DESC');
       $stmt = $q->prepare();
       $stmt->execute();
       $colorsMaximumImage = $stmt->fetchAll(PDO::FETCH_ASSOC);

       /**
          * Now the tricky part begins, we calculate weight of image based on count value logarithm. That way repeated string stays relativy low, and search gets boost by color
          * */            
       $colorIndex = array();
       foreach ($colorsMaximumImage as $color)
       {
           $colorIndex[] = str_repeat(' pld_'.$color['pallete_id'],round(log($color['count'])));
       }
       
       
       $db = ezcDbInstance::get();
       $stmt = $db->prepare('UPDATE `lh_gallery_sphinx_search` SET colors = :colors WHERE id = :id LIMIT 1');
       $stmt->bindValue( ':id',$image->pid);
       $stmt->bindValue( ':colors',trim(implode(' ',$colorIndex)));

       $stmt->execute(); 
   }
   
   /**
    * We cannot use __set options for image album because of findIterator, it does not reset's album internal variable.
    * 
    * */
   public static function indexImage($image,$checkDelay = false) {

       if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'sphinx', 'enabled' ) == true && $image->approved == 1 && ($checkDelay == false || ($checkDelay == true && erConfigClassLhConfig::getInstance()->conf->getSetting( 'sphinx', 'delay_index' ) == false))) {

           $imageIndex = new erLhcoreClassModelGallerySphinxSearch();

           $searchBody = array();
           $searchBody[] = $image->keywords;
           $searchBody[] = $image->pwidth.'x'.$image->pheight;

           $album = erLhcoreClassModelGalleryAlbum::fetch($image->aid);

           $searchBody[] = $album->title;
           $searchBody[] = $album->description;
           $searchBody[] = $album->keyword;

           $albumCategory = erLhcoreClassModelGalleryCategory::fetch($album->category);
           $searchBody[] = $albumCategory->name;
           $searchBody[] = $albumCategory->description;

           $imageIndex->id   = $image->pid;
           $imageIndex->pid   = $image->pid;
           $imageIndex->title = (string)$image->title;
           $imageIndex->hits  = $image->hits;
           $imageIndex->mtime = $image->mtime;
           $imageIndex->caption = (string)$image->caption;
           $imageIndex->comtime = $image->comtime;
           $imageIndex->rtime = $image->rtime;
           $imageIndex->pic_rating = $image->pic_rating;
           $imageIndex->votes = $image->votes;
           $imageIndex->pwidth = $image->pwidth;
           $imageIndex->pheight = $image->pheight;
           $imageIndex->file_path = str_replace(array('-','_','/'),array(' ',' ',' '),$image->filepath);
           $imageIndex->filename = str_replace(array('-','_'),array(' ',' '),$image->filename);
           $imageIndex->text_index = implode(' ',array_filter($searchBody));


           $session = erLhcoreClassGallery::getSession();

           $q = $session->database->createSelectQuery( );
           $q->select( 'pallete_id,count' )->from( 'lh_gallery_pallete_images' );
           $q->where(
           $q->expr->eq( 'pid', $q->bindValue($image->pid) )
           );

           // We use only first 40 records
           $q->limit(40,0);
           $q->orderBy('count DESC');
           $stmt = $q->prepare();
           $stmt->execute();
           $colorsMaximumImage = $stmt->fetchAll(PDO::FETCH_ASSOC);

           /**
              * Now the tricky part begins, we calculate weight of image based on count value logarithm. That way repeated string stays relativy low, and search gets boost by color
              * */            
           $colorIndex = array();
           foreach ($colorsMaximumImage as $color)
           {
               $colorIndex[] = str_repeat(' pld_'.$color['pallete_id'],round(log($color['count'])));
           }

           $imageIndex->colors = trim(implode(' ',$colorIndex));

           erLhcoreClassGallery::getSession()->saveOrUpdate($imageIndex);
       } elseif ($image->approved == 0) {
           self::removeImage($image->pid);
       }
   }
      
   public $id = null;
   public $pid = null;
   public $title = '';
   public $caption = '';
   public $filename = '';
   public $file_path = '';
   public $mtime = 0;
   public $comtime = 0;
   public $rtime = 0;
   public $pic_rating = 0;
   public $votes = 0;
   public $hits = 0;
   public $pwidth = 0;
   public $pheight = 0;
   public $colors = '';
   public $text_index = '';

}


?>