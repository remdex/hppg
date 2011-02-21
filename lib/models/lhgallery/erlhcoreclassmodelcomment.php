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
       $paramsDefault = array('limit' => 100, 'offset' => 0);
       
       $params = array_merge($paramsDefault,$paramsSearch);
       
       $session = erLhcoreClassGallery::getSession();
       $q = $session->createFindQuery( 'erLhcoreClassModelGalleryComment' );  
       
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
      $q->orderBy(isset($params['sort']) ? $params['sort'] : 'msg_id DESC' ); 
      
      $sql = isset($params['cache_key']) ? $params['cache_key'] : md5(erLhcoreClassGallery::multi_implode(',',$params));
              
      $cache = CSCacheAPC::getMem();      
      if (($objects = $cache->restore($sql)) === false)
      {      
          $objects = $session->find( $q );
          $cache->store($sql,$objects);
      }         
              
      return $objects; 
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
	   $cache->delete('comments_'.$pid);
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