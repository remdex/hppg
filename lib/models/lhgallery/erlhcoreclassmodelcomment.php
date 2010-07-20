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
       );
   }
   
   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
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
               $conditions[] = $q->expr->in( $field, $q->bindValue($fieldValue) );
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
      
      $sql = isset($params['cache_key']) ? $params['cache_key'] : md5($q->__toString());
              
      $cache = CSCacheAPC::getMem();      
      if (($objects = $cache->restore($sql)) === false)
      {      
          $objects = $session->find( $q, 'erLhcoreClassModelGalleryComment' );
          $cache->store($sql,$objects);
      }         
              
      return $objects; 
   }
   
   public static function isSpam($body)
   {
       $badWords = '/http|suck|fuck/i';
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
}


?>