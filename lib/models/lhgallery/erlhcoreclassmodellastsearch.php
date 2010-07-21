<?

class erLhcoreClassModelGalleryLastSearch {
        
   public function getState()
   {
       return array(
               'id'          => $this->id,                     
               'countresult' => $this->countresult,             
               'keyword'     => $this->keyword            
       );
   }
   
   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   }
   
   public static function addSearch($keyword,$search_count)
   {
       $db = ezcDbInstance::get();
       $stmt = $db->prepare('SELECT count(id) FROM `lh_gallery_lastsearch` WHERE keyword = :keyword');  
       $stmt->bindValue( ':keyword',$keyword);
       $stmt->execute(); 
       $count = $stmt->fetchColumn(); 

       if  ($count == 0     )
       {       
           $search = new erLhcoreClassModelGalleryLastSearch();
           $search->keyword = $keyword;
           $search->countresult = $search_count;       
           erLhcoreClassGallery::getSession()->save($search);
           
           //Clear cache
           $cache = CSCacheAPC::getMem();            
           $cache->delete('last_searches'); 
           
           $stmt = $db->prepare('SELECT id FROM `lh_gallery_lastsearch` order by id desc limit 9,1');    
           $stmt->execute();
           $idlast = $stmt->fetchColumn();        
                    
           $stmt = $db->prepare('DELETE FROM `lh_gallery_lastsearch` WHERE id < :id_last'); 
           $stmt->bindValue( ':id_last',$idlast);  
           $stmt->execute();    
       }
   }
   
   public static function getSearches($paramsSearch = array())
   {
            
      $cache = CSCacheAPC::getMem();      
      if (($result = $cache->restore('last_searches')) !== false)
      {
          return $result;
      } 
      
       
       $paramsDefault = array('limit' => 32, 'offset' => 0);       
       $params = array_merge($paramsDefault,$paramsSearch);
       
       $session = erLhcoreClassGallery::getSession();
       $q = $session->createFindQuery( 'erLhcoreClassModelGalleryLastSearch' );  
       
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
               $conditions[] = $q->expr->lt( $field,$q->bindValue( $fieldValue ));
           } 
      }
      
      if (isset($params['filtergt']) && count($params['filtergt']) > 0)
       {
           foreach ($params['filtergt'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->gt( $field,$q->bindValue( $fieldValue ));
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
              
      $objects = $session->find( $q, 'erLhcoreClassModelGalleryLastSearch' );
      
      $cache->store('last_searches',$objects);
           
      return $objects; 
   }
       
   public $id = null;
   public $countresult = '';
   public $keyword = '';

}


?>