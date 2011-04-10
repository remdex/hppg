<?php

class erLhcoreClassModelGalleryRateBanIP {
        
   public function getState()
   {
       return array(
               'id'      => $this->id,
               'ip'      => $this->ip        
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
       $hitpopular = erLhcoreClassGallery::getSession('slave')->load( 'erLhcoreClassModelGalleryRateBanIP', (int)$pid );     
       return $hitpopular;
   }
      
   public function removeThis() {
   	   		                
   		erLhcoreClassGallery::getSession()->delete($this);   		   		
   }
   
   
   public static function getCount($params = array())
   {
       $session = erLhcoreClassGallery::getSession('slave');
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_gallery_images_rate_ban_ip" );     
         
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
   
   public static function getList($paramsSearch = array())
   {             
      $paramsDefault = array('limit' => 32, 'offset' => 0);
      
      $params = array_merge($paramsDefault,$paramsSearch);
      
      $session = erLhcoreClassGallery::getSession('slave');
      $q = $session->createFindQuery( 'erLhcoreClassModelGalleryRateBanIP' );  
      
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
      
      $q->orderBy(isset($params['sort']) ? $params['sort'] : 'id asc' ); 
      
      $objects = $session->find( $q );      
      
      return $objects; 
   }
   
   public function saveThis()
   {
       erLhcoreClassGallery::getSession()->saveOrUpdate($this);
   }
   
   public $id = null;
   public $ip = null; 
   
}

?>