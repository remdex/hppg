<?php

class erLhcoreClassGallery{
    
   static private $sphinxClient = NULL;
    
   function __construct()
   {
 
   }
   
   public static function getSession($type = false)
   {
        if ($type === false && !isset( self::$persistentSession ) )
        {            
            self::$persistentSession = new ezcPersistentSession(
                ezcDbInstance::get(),
                new ezcPersistentCodeManager( './pos/lhgallery' )
            );
        } elseif ($type !== false && !isset( self::$persistentSessionSlave ) ) {            
            self::$persistentSessionSlave = new ezcPersistentSession(
                ezcDbInstance::get($type),
                new ezcPersistentCodeManager( './pos/lhgallery' )
            );            
        }
        
        return $type === false ? self::$persistentSession : self::$persistentSessionSlave;
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
        $sphinxCacheVersion = $cache->getCacheVersion('sphinx_cache_version');      
        $cacheKey = md5('SphinxSearchMulti_VersionCache'.$sphinxCacheVersion.erLhcoreClassGallery::multi_implode(',',$queryesBatch));
      }
          
              
      if ($cacheEnabled == false || ($resultReturn = $cache->restore($cacheKey)) === false)
      {
            $cl = self::getSphinxInstance();
            
            $cfg = erConfigClassLhConfig::getInstance();
            
            $maxReturn = $cfg->getSetting( 'sphinx', 'max_matches' );
            $wildCardEnabled = $cfg->getSetting( 'sphinx', 'enabled_wildcard');
            $sphinxIndex = $cfg->getSetting( 'sphinx', 'index' );  
            $extendedSearch = $cfg->getSetting( 'color_search', 'extended_search');
            $faceSearch = $cfg->getSetting( 'face_search', 'enabled');
            $resultItems = array();
            $hasGroupFilter = false;
            $fetchedGroupFromCache = false;
            
            foreach ($queryesBatch as $params) {
                  $executeSearch = true;
                  
                  $cl->ResetFilters();
                  $cl->SetSelect('');
                  $cl->ResetGroupBy('');
                                                
                  $cl->SetLimits(isset($params['SearchOffset']) ? (int)$params['SearchOffset'] : 0, isset($params['SearchLimit']) ? (int)$params['SearchLimit'] : 20,$maxReturn);
                    
                  $filter = isset($params['Filter']) ? $params['Filter'] : array();  
                  
                  if (isset($params['keyword'])) {
                    $params['keyword'] = str_replace('.',' ',$cl->EscapeString($params['keyword']));
                  }
                  
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
                  // Currently we only support all and any match modes
                  $matchModeAll = false;
                  if (isset($params['MatchMode'])) {          
                    switch ($params['MatchMode']) {
                    	case 'all':
                    		  $cl->SetMatchMode(SPH_MATCH_ALL);
                    		  $matchModeAll = true;
                    		break;
                    
                    	default:
                    		break;
                    }
                  }
      
                  $cl->SetSortMode(SPH_SORT_EXTENDED, isset($params['sort']) ? $params['sort'] : '@id DESC');
            
                  $startAppend = $wildCardEnabled == true ? '*' : '';
                  
                
                  
                  $colorSearchText = '';
                  if ((isset($params['color_filter']) && count($params['color_filter']) > 0)  || (isset($params['ncolor_filter']) && count($params['ncolor_filter']) > 0)){
                      $colorSearchText = '';
                      $selectPart = array();
                      foreach ($params['color_filter'] as $color_id)
                      {
                          $colorSearchText .= ' pld'.$color_id;
                          $selectPart[] = "ln(pld{$color_id}+1)"; // +1 to avoid infinity
                      }  
                      
                      // Must not be present
                      foreach ($params['ncolor_filter'] as $color_id)
                      {
                          $colorSearchText .= ' -pld'.$color_id;
                      } 
          
                      // Works best for search by color, like we are repeating color multiple times, 
                      // that way we get almoust the same result as using database
                      // Reference:
                      // http://sphinxsearch.com/docs/current.html#api-func-setrankingmode
                      if (isset($params['color_search_mode'])) {
                                                    
                        $cl->SetMatchMode( SPH_MATCH_EXTENDED2);
                        if (count($params['color_filter']) == 1 || erConfigClassLhConfig::getInstance()->getSetting( 'color_search', 'extended_search') == false) { // If one color we use internal wordcount algorithm                
                            if (!empty($params['color_filter'])){             
                                $cl->SetRankingMode(SPH_RANK_WORDCOUNT);
                            } else {
                                // Just make sure that atleast one color is set              
                                $colorSearchText = implode(' ',array_unique(explode(' ',trim($colorSearchText)))).' imgan';
                            }
                
                        } else {
                            $colorSearchText = implode(' ',array_unique(explode(' ',trim($colorSearchText))));
                            if (isset($params['color_filter']) && count($params['color_filter']) > 0 ) {
                                $cl->SetRankingMode(SPH_RANK_NONE); 
                                $cl->SetSelect('FLOOR(('.implode('+',$selectPart).')*10000) as custom_match'); 
                            } else {
                                $colorSearchText .= ' imgan';
                            }
                        }
                        
                      }  else {  // Works best then keyword and color is used        
                        $cl->SetMatchMode( SPH_MATCH_EXTENDED2);
                        $params['keyword'] = '('.implode($matchModeAll == true ? ' & ' : ' | ',explode(' ',trim($params['keyword']).$startAppend)).') & ';
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
                  
                  if (isset($params['group_by_album']) && $params['group_by_album'] == true) { 
                      
                      if (!isset($sphinxCacheVersion)){
                          $cache = CSCacheAPC::getMem();
                          $sphinxCacheVersion = $cache->getCacheVersion('sphinx_cache_version');
                      }      
                      
                      $paramsCache = $params;
                      if ( isset($paramsCache['Filter']['album_id']) ) {
                            unset($paramsCache['Filter']['album_id']);
                      }
                       
                      if ( isset($paramsCache['sort']) ) {
                          unset($paramsCache['sort']);
                      }
                        
                      if ( isset($paramsCache['SearchOffset']) ) {
                          unset($paramsCache['SearchOffset']);
                      }
                      
                      if ( isset($paramsCache['SearchLimit']) ) {
                          unset($paramsCache['SearchLimit']);
                      }
              
                      $paramsCache = array_filter($paramsCache);
                      ksort($paramsCache);                
                                            
                      $cacheKeyGroup =  md5('SphinxSearch_VersionCacheFacet'.$sphinxCacheVersion.erLhcoreClassGallery::multi_implode(',',$paramsCache));
                      $hasGroupFilter = true;
                      
                      if ( ($cacheGroupResult = $cache->restore($cacheKeyGroup)) !== false ) { 
                          $fetchedGroupFromCache = true;
                      } else {
                          $cl->SetGroupBy( 'album_id', SPH_GROUPBY_ATTR, '@count desc' );
                          $cl->ResetFilterByAttribute('album_id');
                          $cl->SetLimits(0,(int)50,$maxReturn);                          
                      }
                  } elseif ( isset($params['group_by_album']) ) { 
                      $executeSearch = false; // Means filter is set, but not need to execute, because facet search is disabled
                  }     
                  
                  if ( $fetchedGroupFromCache == false && $executeSearch == true) {
                      if ($asSingle == false){
                          $cl->AddQuery( (isset($params['keyword']) && trim($params['keyword']) != '') ? trim($params['keyword']).$startAppend.$colorSearchText : trim($colorSearchText), $sphinxIndex );
                      } else {
                          $resultItems[] = $cl->Query( (isset($params['keyword']) && trim($params['keyword']) != '') ? trim($params['keyword']).$startAppend.$colorSearchText : trim($colorSearchText), $sphinxIndex );
                      }
                  }
            }
            
            if ($asSingle == false) {
                $resultItems = $cl->RunQueries();
            }
           
            $resultReturn = array();
           
            $idMatchGroup = array();
            $idMatchGroupData = array();
                
            if ($hasGroupFilter == true)
            { 
       
                if ( $fetchedGroupFromCache == true ) {
                    $idMatchGroup = $cacheGroupResult['id_match_group'];
                    $idMatchGroupData = $cacheGroupResult['id_match_group_data'];
                } else {
                    $resultGroup = array_pop($resultItems); // Last return is always group if it's enabled                
                    foreach ($resultGroup['matches'] as $item) 
                    { 
                        $idMatchGroup[$item['attrs']['album_id']] = null;
                        $idMatchGroupData[$item['attrs']['album_id']] = $item['attrs']['@count'];
                    }
                  
                    $listObjects = erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('limit' => 50,'filterin'=> array('aid' => array_keys($idMatchGroup))));
                  
                    foreach ($listObjects as $object)
                    {     
                      $idMatchGroup[$object->aid] = $object;
                    }
                    
                    $cache->store($cacheKeyGroup,array('id_match_group' => $idMatchGroup,'id_match_group_data' => $idMatchGroupData),12000);                                
                }
            }
            
            
                        
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
                $listObjects = erLhcoreClassModelGalleryImage::getImages(array('ignore_fields' => (isset($params['ignore_fields']) ? $params['ignore_fields'] : array()),'filterin'=> array('pid' => $imagesIDToFetch)));
            } else {
                foreach ($resultItems as $keyQuery => $result)
                {
                    $resultReturn[$keyQuery] = array('total_found' => 0,'list' => array());
                }
                $resultReturn[] = array('facet_list' => $idMatchGroup, 'facet_data' => $idMatchGroupData);
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
            	   
                  $resultReturn[$keyQuery] = array('total_found' => $result['total_found'],'list' => $idMatch );
            }  
            
            $resultReturn[] = array('facet_list' => $idMatchGroup, 'facet_data' => $idMatchGroupData);
            
            if ($cacheEnabled == true)
            $cache->store($cacheKey,$resultReturn,12000); 
      }
      
      return $resultReturn;
   }
   
   static function getSphinxInstance() {
        if (self::$sphinxClient == NULL) {
            self::$sphinxClient = new SphinxClient();
            self::$sphinxClient->SetServer( erConfigClassLhConfig::getInstance()->getSetting( 'sphinx', 'host' ), erConfigClassLhConfig::getInstance()->getSetting( 'sphinx', 'port' ) );
            self::$sphinxClient->SetMatchMode( SPH_MATCH_ANY  );
        }
        return self::$sphinxClient;
   }
    
   public static function searchSphinx($params = array('SearchLimit' => 20),$cacheEnabled = true)  
   {
      if ($cacheEnabled == true ) {
        $cache = CSCacheAPC::getMem();
        $sphinxCacheVersion = $cache->getCacheVersion('sphinx_cache_version');
        $cacheKey =  md5('SphinxSearch_VersionCache'.$sphinxCacheVersion.erLhcoreClassGallery::multi_implode(',',$params));
      }
      
      if ($cacheEnabled == false || ($resultReturn = $cache->restore($cacheKey)) === false)
      {
      
      $cl = self::getSphinxInstance();
      $cl->ResetFilters();
      $cl->SetSelect('');
      $maxMatches = erConfigClassLhConfig::getInstance()->getSetting( 'sphinx', 'max_matches' );    
      $extendedColorSearch = erConfigClassLhConfig::getInstance()->getSetting( 'color_search', 'extended_search');                
      $faceSearch = erConfigClassLhConfig::getInstance()->getSetting( 'face_search', 'enabled');
            
      $cl->SetLimits(isset($params['SearchOffset']) ? (int)$params['SearchOffset'] : 0,(int)$params['SearchLimit'],$maxMatches);
                    
      $filter = isset($params['Filter']) ? $params['Filter'] : array();  
      
      if (isset($params['keyword'])) { 
        $params['keyword'] = str_replace('.',' ',$cl->EscapeString($params['keyword']));
      }
      
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
      
      // Currently we only support all and any match modes
      $matchModeAll = false;
      if (isset($params['MatchMode'])) {          
        switch ($params['MatchMode']) {
        	case 'all':
        		  $cl->SetMatchMode(SPH_MATCH_ALL);
        		  $matchModeAll = true;
        		break;
        
        	default:
        		break;
        }
      }
      
      $cl->SetSortMode(SPH_SORT_EXTENDED, isset($params['sort']) ? $params['sort'] : '@id DESC');

      $startAppend = erConfigClassLhConfig::getInstance()->getSetting( 'sphinx', 'enabled_wildcard') == true ? '*' : '';
      
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
      if ( (isset($params['color_filter']) && count($params['color_filter']) > 0) || (isset($params['ncolor_filter']) && count($params['ncolor_filter']) > 0) ){
          $colorSearchText = '';
          $selectPart = array();
          
          if (isset($params['color_filter'])){
              foreach ($params['color_filter'] as $color_id)
              {
                  $colorSearchText .= ' pld'.$color_id;
                  $selectPart[] = "ln(pld{$color_id}+1)"; // +1 to avoid infinity
              }
          }
          
          if (isset($params['ncolor_filter'])){
              // Must not be present
              foreach ($params['ncolor_filter'] as $color_id)
              {
                  $colorSearchText .= ' -pld'.$color_id;
              }      
          }
         
          // Works best for search by color, like we are repeating color multiple times, 
          // that way we get almoust the same result as using database
          // Reference:
          // http://sphinxsearch.com/docs/current.html#api-func-setrankingmode
          if (isset($params['color_search_mode'])) {
              
            $cl->SetMatchMode( SPH_MATCH_EXTENDED2);  
   
            if (count($params['color_filter']) == 1 || $extendedColorSearch == false) { // If one color we use internal wordcount algorithm  
                
                if (!empty($params['color_filter'])){             
                    $cl->SetRankingMode(SPH_RANK_WORDCOUNT);
                } else {
                    // Just make sure that atleast one color is set              
                    $colorSearchText = implode(' ',array_unique(explode(' ',trim($colorSearchText)))).' imgan';
                }
                
            } else {
                // Just make sure that atleast one color is set              
                $colorSearchText = implode(' ',array_unique(explode(' ',trim($colorSearchText)))); 

                if (isset($params['color_filter']) && count($params['color_filter']) > 0 ) {               
                    $cl->SetRankingMode(SPH_RANK_NONE); 
                    $cl->SetSelect('FLOOR(('.implode('+',$selectPart).')*10000) as custom_match'); 
                } else {
                    $colorSearchText .= ' imgan';
                }
            }
            
          } else {  // Works best then keyword and color is used        
            $cl->SetMatchMode( SPH_MATCH_EXTENDED2);                                                       
            $params['keyword'] = '('.implode($matchModeAll == true ? ' & ' : ' | ',explode(' ',trim($params['keyword']).$startAppend)).') & ';
            $startAppend = '';
            $cl->SetRankingMode(SPH_RANK_BM25);
          }
      }   

      
      $result = $cl->AddQuery( (isset($params['keyword']) && trim($params['keyword']) != '') ? trim($params['keyword']).$startAppend.$colorSearchText : trim($colorSearchText), erConfigClassLhConfig::getInstance()->getSetting( 'sphinx', 'index' ) );
      
      $idMatchGroup = array();
      $idMatchGroupData = array();
      $fetchedGroupFromCache = false;
      
      if (isset($params['group_by_album'])) { 
          
              if (!isset($sphinxCacheVersion)){
                  $cache = CSCacheAPC::getMem();
                  $sphinxCacheVersion = $cache->getCacheVersion('sphinx_cache_version');
              }      
              
              $paramsCache = $params;
              if ( isset($paramsCache['Filter']['album_id']) ) {
                    unset($paramsCache['Filter']['album_id']);
              }
               
              if ( isset($paramsCache['sort']) ) {
                  unset($paramsCache['sort']);
              }
                
              if ( isset($paramsCache['SearchOffset']) ) {
                  unset($paramsCache['SearchOffset']);
              } 
               
              if ( isset($paramsCache['SearchLimit']) ) {
                  unset($paramsCache['SearchLimit']);
              }
                
              $paramsCache = array_filter($paramsCache);
              ksort($paramsCache);
                            
              $cacheKeyGroup =  md5('SphinxSearch_VersionCacheFacet'.$sphinxCacheVersion.erLhcoreClassGallery::multi_implode(',',$paramsCache));
                
              if ( ($cacheGroupResult = $cache->restore($cacheKeyGroup)) !== false ) { 
                    $fetchedGroupFromCache = true;
              } else {
                    $cl->SetGroupBy( 'album_id', SPH_GROUPBY_ATTR, '@count desc' );
                    $cl->ResetFilterByAttribute('album_id');
                    $cl->SetLimits(0,(int)50,$maxMatches);              
                    $cl->AddQuery( (isset($params['keyword']) && trim($params['keyword']) != '') ? trim($params['keyword']).$startAppend.$colorSearchText : trim($colorSearchText), erConfigClassLhConfig::getInstance()->getSetting( 'sphinx', 'index' ) );
              }
      }
      
      $resultArray = $cl->RunQueries();
      $result = array_shift($resultArray);
           
      if ($result['total_found'] == 0 || !isset($result['matches'])){
          if (isset($params['relevance'])) { 
              return 1;  
          } else {
            return array('total_found' => 0,'list' => null, 'facet_list' => $idMatchGroup, 'facet_data' => $idMatchGroupData);
          }      
      }
      
      if ( isset($params['group_by_album']) ) {  
                  
            if ( $fetchedGroupFromCache == true ) {
                $idMatchGroup = $cacheGroupResult['id_match_group'];
                $idMatchGroupData = $cacheGroupResult['id_match_group_data'];
            } else {
                $resultGroup = array_shift($resultArray);
                $idMatchGroup = array();
                $idMatchGroupData = array();
              
                foreach ($resultGroup['matches'] as $item) 
                { 
                    $idMatchGroup[$item['attrs']['album_id']] = null;
                    $idMatchGroupData[$item['attrs']['album_id']] = $item['attrs']['@count'];
                }
              
                $listObjects = erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('limit' => 50,'filterin'=> array('aid' => array_keys($idMatchGroup))));
              
                foreach ($listObjects as $object)
                {     
                    $idMatchGroup[$object->aid] = $object;
                }
                
                $cache->store($cacheKeyGroup,array('id_match_group' => $idMatchGroup,'id_match_group_data' => $idMatchGroupData),12000);                                
            }
      }
      
      $idMatch = array();
        
      if (isset($params['relevance'])) {          
          $itemCurrent = array_shift($result['matches']);
          
          if (!isset($params['color_search_mode']) || count($params['color_filter']) == 1 || empty($params['color_filter']) || $extendedColorSearch == false) {
            $relevanceValue = $itemCurrent['weight'];
            
          } else {
            if (isset($itemCurrent['attrs']['custom_match'])){
                $relevanceValue = $itemCurrent['attrs']['custom_match'];
            } else {
                $relevanceValue = $itemCurrent['weight'];
            }
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
          	return array('total_found' => 0,'list' => null, 'facet_list' => $idMatchGroup, 'facet_data' => $idMatchGroupData);   
        
      $listObjects = erLhcoreClassModelGalleryImage::getImages(array('ignore_fields' => (isset($params['ignore_fields']) ? $params['ignore_fields'] : array()), 'filterin'=> array('pid' => array_keys($idMatch))));
      
      foreach ($listObjects as $object)
      {     
          $idMatch[$object->pid] = $object;
      }     
       
      if ($result['total_found'] > $maxMatches) {
          $result['total_found'] = $maxMatches;
      }
      
      $resultReturn = array('total_found' => $result['total_found'],'list' => $idMatch, 'facet_list' => $idMatchGroup, 'facet_data' => $idMatchGroupData);
      
      if ($cacheEnabled == true) {
            $cache->store($cacheKey,$resultReturn,12000);
      } 
        
      }
      
      return $resultReturn;
       
   }
        
   // For all others
   private static $persistentSession;
   
   // For selects
   private static $persistentSessionSlave;

}


?>