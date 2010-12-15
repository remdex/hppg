<?php

class erLhcoreClassModelGalleryPallete {
        
   public function getState()
   {
       return array(
               'id'          => $this->id,
               'red'         => $this->red,             
               'green'       => $this->green,             
               'blue'        => $this->blue            
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
       $session = erLhcoreClassGallery::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(pid)" )->from( "lh_gallery_pallete_images" );     
         
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
   public static function getPictureDominantColors($pid,$limit = 5)
   {
       $session = erLhcoreClassGallery::getSession();
       
       $q = $session->createFindQuery( 'erLhcoreClassModelGalleryPallete' );

       $q2 = $q->subSelect();
       $q2->select( 'pallete_id' )->from( 'lh_gallery_pallete_images' );

       $q2->where( 
                         $q2->expr->eq( 'pid', $q->bindValue($pid) )   
              );
       $q2->limit($limit,0);
       $q2->orderBy('count DESC');

       $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_pallete.id', 'items.pallete_id' );   

       $objects = $session->find( $q ); 

       return $objects;  
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
      $q->orderBy(isset($params['sort']) ? $params['sort'] : 'id DESC' ); 
       
      $objects = $session->find( $q );
                           
      return $objects; 
   }
   
   
   public static function getImages($paramsSearch = array())
   {       
       $paramsDefault = array('limit' => 100, 'offset' => 0);
       
       $params = array_merge($paramsDefault,$paramsSearch);
              
       $session = erLhcoreClassGallery::getSession();
       $q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' ); 

       $q2 = $q->subSelect();
       $q2->select( 'pid' )->from( 'lh_gallery_pallete_images' );
       
       $conditions = array();
       
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
      
      $q2->orderBy(isset($params['sort']) ? $params['sort'] : 'count DESC, pid DESC');
      
      $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
             
      $objects = $session->find( $q );
      
      return $objects;      
   }
     
   public $id = null;
   public $red = null;
   public $green = null;
   public $blue = null;

}


?>