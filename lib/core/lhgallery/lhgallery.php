<?php

class erLhcoreClassGallery{
    
    
    
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
   
   public static function searchSphinx($params = array('SearchLimit' => 20))  
   {
      $cl = new SphinxClient();
      $cl->SetServer( erConfigClassLhConfig::getInstance()->conf->getSetting( 'sphinx', 'host' ), erConfigClassLhConfig::getInstance()->conf->getSetting( 'sphinx', 'port' ) );
      $cl->SetMatchMode( SPH_MATCH_ALL  );
      $cl->SetLimits(isset($params['SearchOffset']) ? (int)$params['SearchOffset'] : 0,(int)$params['SearchLimit'],1000000);
                    
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

      if (isset($params['custom_filter'])){
        $cl->SetSelect ( $params['custom_filter']['filter'] );
        $cl->SetFilter ( $params['custom_filter']['filter_name'], array(1) );
      }
      
      $cl->SetSortMode(SPH_SORT_EXTENDED, isset($params['sort']) ? $params['sort'] : '@id DESC');
                     
      $result = $cl->Query( isset($params['keyword']) ? trim($params['keyword']) : '', erConfigClassLhConfig::getInstance()->conf->getSetting( 'sphinx', 'index' ) );
      
      if ($result['total_found'] == 0)
      return array('total_found' => 0,'list' => null);
      
      $idMatch = array();
        
      foreach ($result['matches'] as $key => $match)
      {
         $idMatch[$key] = null;
      }
	      
        
      $listObjects = erLhcoreClassModelGalleryImage::getImages(array('filterin'=> array('pid' => array_keys($idMatch))));
	        
      foreach ($listObjects as $object)
      {
          $idMatch[$object->pid] = $object;
      }     
       
      return array('total_found' => $result['total_found'],'list' => $idMatch);   
   }
        
   private static $persistentSession;

}


?>