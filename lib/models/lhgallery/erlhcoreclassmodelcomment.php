<?php

class erLhcoreClassModelGalleryComment {
        
   public function getState()
   {
       return array(
               'pid'            => $this->pid,
               'msg_id'         => $this->msg_id,             
               'msg_author'     => $this->msg_author,             
               'msg_body'       => $this->msg_body,             
               'msg_date'       => $this->msg_date,             
               'msg_hdr_ip'     => $this->msg_hdr_ip,             
               'author_md5_id'  => $this->author_md5_id,             
               'author_id'      => $this->author_id,             
               'lang'           => $this->lang,
       );
   }
   
   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   } 
   
   public static function fetch($msg_id)
   {
       $msg = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryComment', (int)$msg_id );
       return $msg;
   }
   
   public function saveThis()
   {
       erLhcoreClassGallery::getSession()->saveOrUpdate( $this );      
   }
   
   public static function getComments($paramsSearch = array())
   {
       $paramsDefault = array('limit' => 10, 'offset' => 0);       
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
       $q = $session->createFindQuery( 'erLhcoreClassModelGalleryComment' );  
       
       $conditions = array(); 
       
       $q2 = $q->subSelect();
       $q2->select( 'msg_id' )->from( 'lh_gallery_comments' );
       
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
      $q2->orderBy(isset($params['sort']) ? $params['sort'] : 'msg_id DESC');
      
      $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_comments.msg_id', 'items.msg_id' );          
            
      $objects = $session->find( $q );

      if (!isset($params['disable_sql_cache']))
      {
              $cache->store($cacheKey,$objects);
      }
           
      return $objects;         
   }
   
   
   public static function getCount($params = array())
   {
       if (!isset($params['disable_sql_cache']))
       {
          $sql = erLhcoreClassGallery::multi_implode(',',$params);  
                       
          $cache = CSCacheAPC::getMem();          
          $cacheKey = isset($params['cache_key']) ? md5($sql.$params['cache_key']) : md5('images_comment_count_site_version_'.$cache->getCacheVersion('site_version').$sql);
          
          if (($result = $cache->restore($cacheKey)) !== false)
          {              
              return $result;
          }       
       }
       
       $session = erLhcoreClassGallery::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(msg_id)" )->from( "lh_gallery_comments" );     
       
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
   
   public function removeThis()
   {      
       $pid = $this->pid;
       
       erLhcoreClassGallery::getSession()->delete($this);
              	   	   
	   // Update image last commented time
	   $db = ezcDbInstance::get();
       $stmt = $db->prepare('UPDATE lh_gallery_images SET comtime = (SELECT UNIX_TIMESTAMP(MAX(msg_date)) FROM lh_gallery_comments WHERE pid = :pid) WHERE pid = :pid_2');
       $stmt->bindValue( ':pid',$pid);
       $stmt->bindValue( ':pid_2',$pid);
       $stmt->execute();
	   
       $image = erLhcoreClassModelGalleryImage::fetch($pid);
       
       // Expires last uploads shard index
	   erLhcoreClassGallery::expireShardIndexByIdentifier(array('last_commented'));
	   
	   $cache = CSCacheAPC::getMem(); 
	   $cache->increaseCacheVersion('last_commented'); 
	   $cache->increaseCacheVersion('comments_'.$pid);
	   $cache->increaseCacheVersion('last_commented_'.$image->aid);
	   $cache->increaseCacheVersion('last_commented_image_version_'.$pid);
	   
	   erLhcoreClassGallery::expireShardIndexByIdentifier(array('album_id_'.$image->aid),array('comtime DESC, pid DESC','comtime ASC, pid ASC'));
	   
	   // Update two attributes
	   erLhcoreClassModelGallerySphinxSearch::indexAttributes($image,array('comtime' => 'comtime'));
   }
      
   
   public static function isSpam($body)
   {
       $badWords = '/suck|fuck/i';
       if (preg_match($badWords,$body))
	   {
	       return true;
	   }
	   
	   return false;
   }
   
   
   public $pid = 0;
   public $msg_id = null;
   public $msg_author = '';
   public $msg_body = '';
   public $msg_date;
   public $msg_hdr_ip = '';
   public $author_md5_id = '';
   public $author_id = 0;
   public $lang = '';
}


?>