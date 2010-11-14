<?

class erLhcoreClassModelGallerySearchHistory {

   public function getState()
   {
       return array(
               'id'            => $this->id,                     
               'countresult'   => $this->countresult,             
               'keyword'       => $this->keyword,            
               'crc32'         => $this->crc32,            
               'last_search'   => $this->last_search,          
               'searches_done' => $this->searches_done,          
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
       $keywordClean = mb_strtolower(trim($keyword));
       $items = erLhcoreClassModelGallerySearchHistory::getSearches(array('limit' => 1,'filter' => array('crc32' => crc32($keywordClean),'keyword' => $keywordClean)));    
          
       if  (count($items) == 0  )
       {       
           $search = new erLhcoreClassModelGallerySearchHistory();
           $search->keyword = $keywordClean;
           $search->countresult = $search_count;       
           $search->last_search = time();       
           $search->searches_done = 1;       
           $search->crc32 = crc32($keywordClean);
           erLhcoreClassGallery::getSession()->save($search);              
       } else {
           $itemCurrent = array_pop($items);
           $itemCurrent->last_search = time();
           $itemCurrent->countresult = $search_count;
           $itemCurrent->searches_done++;
           erLhcoreClassGallery::getSession()->update($itemCurrent);   
       }
   }
   
   public static function getSearches($paramsSearch = array())
   {
       $paramsDefault = array('limit' => 32, 'offset' => 0);       
       $params = array_merge($paramsDefault,$paramsSearch);
       
       $session = erLhcoreClassGallery::getSession();
       $q = $session->createFindQuery( 'erLhcoreClassModelGallerySearchHistory' );  
       
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
               $conditions[] = $q->expr->in( $field, $fieldValue );
           } 
      }
      
      if (isset($params['filterlt']) && count($params['filterlt']) > 0)
       {
           foreach ($params['filterlt'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->lt( $field, $fieldValue );
           } 
      }
      
      if (isset($params['filtergt']) && count($params['filtergt']) > 0)
       {
           foreach ($params['filtergt'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->gt( $field, $fieldValue );
           } 
      }      
      
      if (count($conditions) > 0)
      {
          $q->where( 
                     $conditions   
          );
      } 
      
      $q->limit($params['limit'],$params['offset']);
                
      $q->orderBy(isset($params['sort']) ? $params['sort'] : 'last_search DESC' ); 
              
      $objects = $session->find( $q );
                    
      return $objects; 
   }
   
   public static function getSearchCount($params = array())
   {
       $session = erLhcoreClassGallery::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_gallery_searchhistory" );     
         
       $conditions = array();
       
       if (isset($params['filter']) && count($params['filter']) > 0)
       {
           foreach ($params['filter'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->eq( $field, $fieldValue );
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
               $conditions[] = $q->expr->lt( $field, $fieldValue );
           } 
      }
      
      if (isset($params['filtergt']) && count($params['filtergt']) > 0)
       {
           foreach ($params['filtergt'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->gt( $field, $fieldValue );
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
       
   public $id = null;
   public $countresult = '';
   public $keyword = '';
   public $crc32 = 0;
   public $last_search = 0;
   public $searches_done = 0;

               
}


?>