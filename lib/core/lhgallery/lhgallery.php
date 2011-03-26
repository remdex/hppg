<?php

class erLhcoreClassGallery{
    
   static private $sphinxClient = NULL;
    
   function __construct()
   {
 
   }
   
   public static function getSession()
   {
        if ( !isset( self::$persistentSession ) )
        {            
            self::$persistentSession = new ezcPersistentSession(
                ezcDbInstance::get(),
                new ezcPersistentCodeManager( './pos/lhgallery' )
            );
        }
        return self::$persistentSession;
   }
      
   public static function multi_implode($glue, $pieces, $key = null)
   {
       $string='';

       if(is_array($pieces))
       {
           reset($pieces);
           while(list($key,$value)=each($pieces))
           {
               $string.=$glue.erLhcoreClassGallery::multi_implode($glue, $value, $key);
           }
       }
       else
       {
           return "{$key}_{$pieces}";
       }

       return trim($string, $glue);
   }
   
   /**
    * Does all the nasty job regarding spliting indexes. Currently only last uploads uses it.
    * 
    * */  
   public static function getShardFilter($params)
   {
       $shardPartSplit = 5000;
       $safeAheadOffset = 100;
       
       if (($params['offset'] / $shardPartSplit) > 1)
       {
           $db = ezcDbInstance::get(); 
           $sortKey = md5($params['sort']);
           $filterKey = md5( erLhcoreClassGallery::multi_implode(',',$params['filter']) );
           $offsetKey = floor($params['offset'] / $shardPartSplit);
           
           // Left offset filter
           $q = $db->createSelectQuery();
           $q->select( 'pid,offset' )
            	->from( 'lh_gallery_shard_limit' )
            	->where(
            	   $q->expr->land(
            	       $q->expr->lte( 'offset', $q->bindValue($params['offset']) ),
            	       $q->expr->eq(  'identifier', $q->bindValue($params['identifier']) ),            	       
            	       $q->expr->eq(  'filter', $q->bindValue($filterKey )),
            	       $q->expr->eq(  'sort', $q->bindValue($sortKey) ) 
            	   ))
            	->limit(1,0)
            	->orderBy('offset DESC');

           $stmt = $q->prepare();
           $stmt->execute();
           $data = $stmt->fetch(PDO::FETCH_ASSOC);
         
           $appendShardData = array('filter_key' => $filterKey, 'offset' => $params['offset'], 'identifier' => $params['identifier'],'sort_key' => $sortKey );
           $returnShardFilter = array('filter' => false,'append_shard' => false );
                       
           $leftFilter = 'filterlte';
           $rightFilter = 'filtergte';
           
           if (isset($params['reverse']) && $params['reverse'] === true) {
               $leftFilter = 'filtergte';
               $rightFilter = 'filterlte';
           }
       
           
           if ($data !== false && $data['pid'] !== false ) {              
               $returnShardFilter['filter'][$leftFilter] = array('pid' => $data['pid']);
               $returnShardFilter['filter']['shard_deduct_limit'] = $data['offset'];                                   
           }
          
           // Right offset filter
           $q2 = $db->createSelectQuery();
           $q2->select( 'pid,offset' )
              ->from( 'lh_gallery_shard_limit' )
              ->where(
            	   $q2->expr->land (            	       
            	       $q2->expr->gte( 'offset', $q2->bindValue($params['offset']) ),
            	       $q2->expr->eq(  'identifier', $q2->bindValue($params['identifier']) ),            	       
            	       $q2->expr->eq(  'filter', $q2->bindValue($filterKey )),
            	       $q2->expr->eq(  'sort', $q2->bindValue($sortKey) ) 
            	   ))
               ->limit(1,0)
               ->orderBy('offset ASC');

           $stmt = $q2->prepare();
           $stmt->execute();
           $dataMin = $stmt->fetch(PDO::FETCH_ASSOC);
           
           if ($dataMin !== false && $dataMin['pid'] !== false && ($dataMin['offset'] > $params['offset'] + ($shardPartSplit/10))) {  
               $returnShardFilter['filter'][$rightFilter] =  array('pid' => $dataMin['pid']);                             
           }
           
           if (isset($data['offset']) && !isset($dataMin['pid']) && ($params['offset'] - $data['offset']) > $shardPartSplit) {               
               $returnShardFilter['append_shard'] = $appendShardData;               
           } elseif (isset($data['offset']) && isset($dataMin['offset']) && ($params['offset'] - $data['offset']) > $shardPartSplit && ($dataMin['offset'] - $params['offset']) > $shardPartSplit) {               
               $returnShardFilter['append_shard'] = $appendShardData;               
           } elseif (!isset($data['offset']) && isset($dataMin['offset']) && ($dataMin['offset']-$params['offset']) > $shardPartSplit) {
               $returnShardFilter['append_shard'] = $appendShardData;
           } elseif (!isset($data['offset']) && !isset($dataMin['offset'])) {
               $returnShardFilter['append_shard'] = $appendShardData;
           } 
       
           return $returnShardFilter;
           
       } else {
           // Offset is less than shard split part, no need any special actions
           return array('filter' => false,'append_shard' => false);
       }
   }
   
   public static function addShardFilter($params) {
       
       //echo "<pre>";
       //print_r($params);
       //echo "</pre>";
       
       $db = ezcDbInstance::get();   		
       $stmt = $db->prepare("REPLACE INTO lh_gallery_shard_limit (pid,offset,sort,filter,identifier) VALUES (:pid,:offset,:sort,:filter,:identifier)");	   
	   $stmt->bindValue( ':pid',$params['pid']);
       $stmt->bindValue( ':offset',$params['offset']);
       $stmt->bindValue( ':sort',$params['sort_key']);
       $stmt->bindValue( ':filter',$params['filter_key']);
       $stmt->bindValue( ':identifier',$params['identifier']);
       $stmt->execute();  
   }
   
   public static function expireShardIndexByIdentifier(array $identifiers,$sorts = array())
   {
       $db = ezcDbInstance::get(); 
       foreach ($identifiers as $identifier) {
           $sortFilter = '';
           
           if (count($sorts) > 0) {
               $sortFilter = ' AND (';               
               $parts = array();
               foreach ($sorts as $sort){
                   $parts[] = "sort = '".md5($sort)."'";
               }
               $sortFilter .= implode(' OR ',$parts).')';
           }
                      
           $stmt = $db->prepare("DELETE FROM lh_gallery_shard_limit WHERE identifier = :identifier {$sortFilter}");	   
    	   $stmt->bindValue( ':identifier',$identifier);         
           $stmt->execute();
       }
   }

   
   public static function searchSphinxMulti($queryesBatch,$cacheEnabled = true,$asSingle = false)
   {
      if ($cacheEnabled == true) {
        $cache = CSCacheAPC::getMem();        
        $cacheKey = md5('SphinxSearchMulti_VersionCache'.$cache->getCacheVersion('sphinx_cache_version').erLhcoreClassGallery::multi_implode(',',$queryesBatch));
      }
      
      if ($cacheEnabled == false || ($resultReturn = $cache->restore($cacheKey)) === false)
      {
            $cl = self::getSphinxInstance();
            
            $cfg = erConfigClassLhConfig::getInstance();
            
            $maxReturn = $cfg->conf->getSetting( 'sphinx', 'max_matches' );
            $wildCardEnabled = $cfg->conf->getSetting( 'sphinx', 'enabled_wildcard');
            $sphinxIndex = $cfg->conf->getSetting( 'sphinx', 'index' );  
            $extendedSearch = $cfg->conf->getSetting( 'color_search', 'extended_search');
            $faceSearch = $cfg->conf->getSetting( 'face_search', 'enabled');
            $resultItems = array();
            
            foreach ($queryesBatch as $params) {
                  
                  $cl->ResetFilters();
                  $cl->SetSelect('');
                                                
                  $cl->SetLimits(isset($params['SearchOffset']) ? (int)$params['SearchOffset'] : 0,(int)$params['SearchLimit'],$maxReturn);
                    
                  $filter = isset($params['Filter']) ? $params['Filter'] : array();  
                   
                  foreach ($filter as $field => $value)  
                  {                     
                      if ( is_numeric( $value ) and $value > 0 )
                      {
                      	 $cl->SetFilter( $field, array((int)$value));
                      }
                      else if ( is_array( $value ) and count( $value ) )
                      {           
                         $cl->SetFilter( $field, $value);
                      }       
                  }
                  
                  if (isset($params['filtergt'])) {
                       foreach ($params['filtergt'] as $attribute => $fieldValue) { 
                           $cl->SetFilterRange( $attribute, (int)0, (int)$fieldValue, true );
                       }
                  }  
                  
                  if (isset($params['filterlt'])) {
                       foreach ($params['filterlt'] as $attribute => $fieldValue) {          
                           $cl->SetFilterRange( $attribute, (int)0, (int)$fieldValue, false );
                       }
                  }
                  
                  if (isset($params['filterfloatgt'])) {
                       foreach ($params['filterfloatgt'] as $attribute => $fieldValue) { 
                           $cl->SetFilterFloatRange( $attribute, (float)0, (float)$fieldValue, true );
                       }
                  }  
                  
                  if (isset($params['filterfloatlt'])) {
                       foreach ($params['filterfloatlt'] as $attribute => $fieldValue) {          
                           $cl->SetFilterFloatRange( $attribute, (float)0, (float)$fieldValue, false );
                       }
                  } 
            
                  if (isset($params['FilterFloat'])) {
                        foreach ($params['FilterFloat'] as $attribute => $fieldValue) {          
                           $cl->SetFilterFloatRange( $attribute, (float)$fieldValue, (float)$fieldValue, false );
                       }
                  }
                  
                  
                  if (isset($params['custom_filter'])){
                    $cl->SetSelect ( $params['custom_filter']['filter'] );
                    $cl->SetFilter ( $params['custom_filter']['filter_name'], array(1) );
                  }
                  
                  $cl->SetSortMode(SPH_SORT_EXTENDED, isset($params['sort']) ? $params['sort'] : '@id DESC');
            
                  $startAppend = $wildCardEnabled == true ? '*' : '';
                  
                  
                  $colorSearchText = '';
                  if (isset($params['color_filter']) && count($params['color_filter']) > 0){
                      $colorSearchText = '';
                      $selectPart = array();
                      foreach ($params['color_filter'] as $color_id)
                      {
                          $colorSearchText .= ' pld'.$color_id;
                          $selectPart[] = "ln(pld{$color_id}+1)"; // +1 to avoid infinity
                      }  
                      
                      // Works best for search by color, like we are repeating color multiple times, 
                      // that way we get almoust the same result as using database
                      // Reference:
                      // http://sphinxsearch.com/docs/current.html#api-func-setrankingmode
                      if (isset($params['color_search_mode'])) {
                                                    
                        $cl->SetMatchMode( SPH_MATCH_EXTENDED2);
                        if (count($params['color_filter']) == 1 || erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'extended_search') == false) { // If one color we use internal wordcount algorithm                
                            $cl->SetRankingMode(SPH_RANK_WORDCOUNT);
                        } else {
                            $colorSearchText = implode(' ',array_unique(explode(' ',trim($colorSearchText))));
                            $cl->SetRankingMode(SPH_RANK_NONE); 
                            $cl->SetSelect('FLOOR(('.implode('+',$selectPart).')*10000) as custom_match'); 
                        }
                        
                      }  else {  // Works best then keyword and color is used        
                        $cl->SetMatchMode( SPH_MATCH_EXTENDED2);
                        $params['keyword'] = '('.implode(' | ',explode(' ',trim($params['keyword']).$startAppend)).') & ';
                        $startAppend = '';
                        $cl->SetRankingMode(SPH_RANK_BM25); 
                      }
                  }
      
                  $weights = array (
                    'colors' => 9,
                    'title' => 10,
                    'caption' => 8,
                    'filename' => 9,
                    'file_path' => 7 
                  );
                                                      
                  if ($faceSearch == true) {                      
                      $weights['face_data'] = 9;
                      if (isset($params['keyword'])) {
                        $params['keyword'] = preg_replace('/^(females|womens|women|woman)/','female',$params['keyword']);                     
                        $params['keyword'] = preg_replace('/^(males|mens|man|men)/','male',$params['keyword']);                     
                        $params['keyword'] = preg_replace('/^(smile)/','smiling',$params['keyword']);
                      }
                  }
                  
                  // Make some weightning
                  $cl->SetFieldWeights($weights);
                  
                  if ($asSingle == false){
                      $cl->AddQuery( (isset($params['keyword']) && trim($params['keyword']) != '') ? trim($params['keyword']).$startAppend.$colorSearchText : trim($colorSearchText), $sphinxIndex );
                  } else {
                      $resultItems[] = $cl->Query( (isset($params['keyword']) && trim($params['keyword']) != '') ? trim($params['keyword']).$startAppend.$colorSearchText : trim($colorSearchText), $sphinxIndex );
                  }
            }
            
            if ($asSingle == false) {
                $resultItems = $cl->RunQueries();
            }
            
            
            $resultReturn = array();
            
            
            // Get ID's witch we need to fetch first
            $imagesIDToFetch = array();
            foreach ($resultItems as $result)
            {
                if ($result['total_found'] != 0 && isset($result['matches'])) {
                    $imagesIDToFetch = array_merge($imagesIDToFetch,array_keys($result['matches']));
                }
            }
            
            // We fetch only unique images
            $imagesIDToFetch = array_unique($imagesIDToFetch);
            if (count($imagesIDToFetch) > 0){            
                $listObjects = erLhcoreClassModelGalleryImage::getImages(array('filterin'=> array('pid' => $imagesIDToFetch)));
            } else {
                foreach ($resultItems as $keyQuery => $result)
                {
                    $resultReturn[$keyQuery] = array('total_found' => 0,'list' => array());
                }
                return $resultReturn;
            }
      
            foreach ($resultItems as $keyQuery => $result)
            {
                  if ($result['total_found'] == 0 || !isset($result['matches'])) {                  
                      $resultReturn[$keyQuery] = array('total_found' => 0,'list' => null);
                      continue;
                  }
                  
                  $idMatch = array();
                   
                  foreach ($result['matches'] as $key => $match)
                  {
                     $idMatch[$key] = null;
                  }
                        
            	  if (count($idMatch) == 0){
                      	$resultReturn[$keyQuery] = array('total_found' => 0,'list' => null);   
                      	continue;
            	  }
                  
            	  foreach ($idMatch as $key => $value) 
            	  {
            	      if ( isset($listObjects[$key]) ) {
            	           $idMatch[$key] = $listObjects[$key];
            	      } else {
            	           unset($idMatch[$key]);
            	      }
            	  }
                  
            	  if ($result['total_found'] > $maxReturn) {
            	      $result['total_found'] = $maxReturn;
            	  }            
            	   
                  $resultReturn[$keyQuery] = array('total_found' => $result['total_found'],'list' => $idMatch);
            }  
                        
            if ($cacheEnabled == true)
            $cache->store($cacheKey,$resultReturn,12000); 
      }
      
      return $resultReturn;
   }
   
   static function getSphinxInstance() {
        if (self::$sphinxClient == NULL) {
            self::$sphinxClient = new SphinxClient();
            self::$sphinxClient->SetServer( erConfigClassLhConfig::getInstance()->conf->getSetting( 'sphinx', 'host' ), erConfigClassLhConfig::getInstance()->conf->getSetting( 'sphinx', 'port' ) );
            self::$sphinxClient->SetMatchMode( SPH_MATCH_ANY  );
        }
        return self::$sphinxClient;
   }
    
   public static function searchSphinx($params = array('SearchLimit' => 20),$cacheEnabled = true)  
   {
      if ($cacheEnabled == true ) {
        $cache = CSCacheAPC::getMem();        
        $cacheKey =  md5('SphinxSearch_VersionCache'.$cache->getCacheVersion('sphinx_cache_version').erLhcoreClassGallery::multi_implode(',',$params));
      }
      
      if ($cacheEnabled == false || ($resultReturn = $cache->restore($cacheKey)) === false)
      {
      
      $cl = self::getSphinxInstance();
      $cl->ResetFilters();
      $cl->SetSelect('');
      $maxMatches = erConfigClassLhConfig::getInstance()->conf->getSetting( 'sphinx', 'max_matches' );    
      $extendedColorSearch = erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'extended_search');                
      $faceSearch = erConfigClassLhConfig::getInstance()->conf->getSetting( 'face_search', 'enabled');
            
      $cl->SetLimits(isset($params['SearchOffset']) ? (int)$params['SearchOffset'] : 0,(int)$params['SearchLimit'],$maxMatches);
                    
      $filter = isset($params['Filter']) ? $params['Filter'] : array();  
       
      foreach ($filter as $field => $value)  
      {                     
          if ( is_numeric( $value ) and $value > 0 )
          {
          	 $cl->SetFilter( $field, array((int)$value));
          }
          else if ( is_array( $value ) and count( $value ) )
          {           
             $cl->SetFilter( $field, $value);
          }       
      }
      
      if (isset($params['filtergt'])) {
           foreach ($params['filtergt'] as $attribute => $fieldValue) { 
               $cl->SetFilterRange( $attribute, (int)0, (int)$fieldValue, true );
           }
      }  
      
      if (isset($params['filterlt'])) {
           foreach ($params['filterlt'] as $attribute => $fieldValue) {          
               $cl->SetFilterRange( $attribute, (int)0, (int)$fieldValue, false );
           }
      } 
      
      if (isset($params['filterfloatgt'])) {
           foreach ($params['filterfloatgt'] as $attribute => $fieldValue) { 
               $cl->SetFilterFloatRange( $attribute, (float)0, (float)$fieldValue, true );
           }
      }  
      
      if (isset($params['filterfloatlt'])) {
           foreach ($params['filterfloatlt'] as $attribute => $fieldValue) {          
               $cl->SetFilterFloatRange( $attribute, (float)0, (float)$fieldValue, false );
           }
      } 

      if (isset($params['FilterFloat'])) {
            foreach ($params['FilterFloat'] as $attribute => $fieldValue) {          
               $cl->SetFilterFloatRange( $attribute, (float)$fieldValue, (float)$fieldValue, false );
           }
      }
                  
      if (isset($params['custom_filter'])){
        $cl->SetSelect ( $params['custom_filter']['filter'] );
        $cl->SetFilter ( $params['custom_filter']['filter_name'], array(1) );
      }
      
      $cl->SetSortMode(SPH_SORT_EXTENDED, isset($params['sort']) ? $params['sort'] : '@id DESC');

      $startAppend = erConfigClassLhConfig::getInstance()->conf->getSetting( 'sphinx', 'enabled_wildcard') == true ? '*' : '';
      
      $weights = array (
        'colors' => 9,
        'title' => 10,
        'caption' => 8,
        'filename' => 9,
        'file_path' => 7 
      );
      
      if ($faceSearch == true) {
          $weights['face_data'] = 9;
          if (isset($params['keyword'])) {   
              $params['keyword'] = preg_replace('/^(females|womans|woman)/','female',$params['keyword']);                     
              $params['keyword'] = preg_replace('/^(males|mans|man)/','male',$params['keyword']);                     
              $params['keyword'] = preg_replace('/^(smile)/','smiling',$params['keyword']);
          }
      }
        
      // Make some weightning
      $cl->SetFieldWeights($weights);
      
      $colorSearchText = '';
      if (isset($params['color_filter']) && count($params['color_filter']) > 0){
          $colorSearchText = '';
          $selectPart = array();
          
          foreach ($params['color_filter'] as $color_id)
          {
              $colorSearchText .= ' pld'.$color_id;
              $selectPart[] = "ln(pld{$color_id}+1)"; // +1 to avoid infinity
          }
          
          // Works best for search by color, like we are repeating color multiple times, 
          // that way we get almoust the same result as using database
          // Reference:
          // http://sphinxsearch.com/docs/current.html#api-func-setrankingmode
          if (isset($params['color_search_mode'])) {
              
            $cl->SetMatchMode( SPH_MATCH_EXTENDED2);  
   
            if (count($params['color_filter']) == 1 || $extendedColorSearch == false) { // If one color we use internal wordcount algorithm                
                $cl->SetRankingMode(SPH_RANK_WORDCOUNT);
            } else {
                // Just make sure that atleast one color is set              
                $colorSearchText = implode(' ',array_unique(explode(' ',trim($colorSearchText))));                               
                $cl->SetRankingMode(SPH_RANK_NONE); 
                $cl->SetSelect('FLOOR(('.implode('+',$selectPart).')*10000) as custom_match');                               
            }
            
          } else {  // Works best then keyword and color is used        
            $cl->SetMatchMode( SPH_MATCH_EXTENDED2);                                                       
            $params['keyword'] = '('.implode(' | ',explode(' ',trim($params['keyword']).$startAppend)).') & ';
            $startAppend = '';
            $cl->SetRankingMode(SPH_RANK_BM25);
          }
      }   
           
      $result = $cl->Query( (isset($params['keyword']) && trim($params['keyword']) != '') ? trim($params['keyword']).$startAppend.$colorSearchText : trim($colorSearchText), erConfigClassLhConfig::getInstance()->conf->getSetting( 'sphinx', 'index' ) );
     
      
      if ($result['total_found'] == 0 || !isset($result['matches'])){
      
          if (isset($params['relevance'])) { 
              return 1;  
          } else {
            return array('total_found' => 0,'list' => null);
          }      
      }
      
      $idMatch = array();
        
      if (isset($params['relevance'])) {          
          $itemCurrent = array_shift($result['matches']);
          
          if (!isset($params['color_search_mode']) || count($params['color_filter']) == 1 || $extendedColorSearch == false) {
            $relevanceValue = $itemCurrent['weight'];
          } else {
            $relevanceValue = $itemCurrent['attrs']['custom_match'];
          }
                    
          if ($cacheEnabled == true ) {
            $cache->store($cacheKey,$relevanceValue,12000);
          }
          return $relevanceValue;
      }
      
      foreach ($result['matches'] as $key => $match)
      {
         $idMatch[$key] = null;
      }
            
	  if (count($idMatch) == 0)
          	return array('total_found' => 0,'list' => null);   
        
      $listObjects = erLhcoreClassModelGalleryImage::getImages(array('filterin'=> array('pid' => array_keys($idMatch))));
      
      foreach ($listObjects as $object)
      {     
          $idMatch[$object->pid] = $object;
      }     
       
      if ($result['total_found'] > $maxMatches) {
          $result['total_found'] = $maxMatches;
      }
      
      $resultReturn = array('total_found' => $result['total_found'],'list' => $idMatch);
      
      if ($cacheEnabled == true) {
            $cache->store($cacheKey,$resultReturn,12000);
      } 
        
      }
      
      return $resultReturn;
       
   }
        
   private static $persistentSession;

}


?>