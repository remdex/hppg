<?

class erLhcoreClassModelGalleryFiletype {

   // Validation handlers from post
   public $handlers = array (
        'IMAGE' => 'erLhcoreClassImageConverter::isPhoto',
        'HTMLV' => 'erLhcoreClassHTMLVConverter::isVideo',
        'SWF'   => 'erLhcoreClassSWFConverter::isSWF',
        'FLV'   => 'erLhcoreClassFLVConverter::isVideo'
   ); 
   
   // Validation handlers from local
   public $handlersLocal = array (
        'IMAGE' => 'erLhcoreClassImageConverter::isPhotoLocal',
        'HTMLV' => 'erLhcoreClassHTMLVConverter::isVideoLocal',
        'SWF'   => 'erLhcoreClassSWFConverter::isSWFLocal',
        'FLV'   => 'erLhcoreClassFLVConverter::isVideoLocal',
   );
   
   // Conversion handlers from post
   public $handlersConversion = array(
        'IMAGE' => 'erLhcoreClassImageConverter::handleUpload',
        'HTMLV' => 'erLhcoreClassHTMLVConverter::handleUpload',
        'SWF'   => 'erLhcoreClassSWFConverter::handleUpload',
        'FLV'   => 'erLhcoreClassFLVConverter::handleUpload'
   );
   
   // Conversion handlers from post
   public $handlersConversionLocal = array(
        'IMAGE' => 'erLhcoreClassImageConverter::handleUploadLocal',
        'HTMLV' => 'erLhcoreClassHTMLVConverter::handleUploadLocal',
        'SWF'   => 'erLhcoreClassSWFConverter::handleUploadLocal',
        'FLV'   => 'erLhcoreClassFLVConverter::handleUploadLocal'
   );
   
   // Conversion handlers from post
   public $handlersConversionBatch = array(
        'IMAGE' => 'erLhcoreClassImageConverter::handleUploadBatch',
        'HTMLV' => 'erLhcoreClassHTMLVConverter::handleUploadBatch',
        'SWF'   => 'erLhcoreClassSWFConverter::handleUploadBatch',
        'FLV'   => 'erLhcoreClassFLVConverter::handleUploadBatch'
   );
   
   public function getState()
   {
       return array(
               'extension'      => $this->extension,                     
               'mime'           => $this->mime,             
               'content'        => $this->content,            
               'player'         => $this->player         
       );
   }
   
   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   }
   
   public static function fetch($extension)
   {
       try {
           return erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryFiletype', $extension );         
       } catch (Exception $e){
           return false;
       }
   }  
   
   /**
    * $file - file variable name
    * 
    * */
   public static function isValid($file)
   {
       if ($_FILES[$file]['error'] == 0)
       {       
           try {
                 $extensionParts = explode('.',$_FILES[$file]['name']);
                 end($extensionParts);
                 
                 $filetype = erLhcoreClassModelGalleryFiletype::fetch(current($extensionParts));
                 
                 if ($filetype instanceof erLhcoreClassModelGalleryFiletype) {                     
                     $result = call_user_func($filetype->handlers[$filetype->player],$file);  

                     if ($result !== false) {
                         return $filetype;
                     }   
                     
                     return false; // Not valid file         
                 } else { 
                     return false; // File type not supported
                 }
               
           } catch (Exception $e) {
               return false; // Any other exception
           }
       
       } else {
           return false; // Failed upload
       }
   }
   
   public static function isValidLocal($file)
   {             
           try {
                 $extensionParts = explode('.',$file);
                 end($extensionParts);
                 
                 $filetype = erLhcoreClassModelGalleryFiletype::fetch(current($extensionParts));
                 
                 if ($filetype instanceof erLhcoreClassModelGalleryFiletype) {                     
                     $result = call_user_func($filetype->handlersLocal[$filetype->player],$file);  

                     if ($result !== false) {
                         return $filetype;
                     }   
                     
                     return false; // Not valid file         
                 } else { 
                     return false; // File type not supported
                 }
               
           } catch (Exception $e) {
               return false; // Any other exception
           }       
       
   }
   
   public function process(& $image, $params)
   {
       call_user_func_array($this->handlersConversion[$this->player],array($image,$params));
   }
   
   public function processLocal(& $image, $params)
   {
       call_user_func_array($this->handlersConversionLocal[$this->player],array($image,$params));
   }
   
   public function processLocalBatch(& $image, $params)
   {
       call_user_func_array($this->handlersConversionBatch[$this->player],array($image,$params));
   }
   
   public static function getList($paramsSearch = array())
   {
       $paramsDefault = array('limit' => 32, 'offset' => 0);       
       $params = array_merge($paramsDefault,$paramsSearch);
       
       $session = erLhcoreClassGallery::getSession();
       $q = $session->createFindQuery( 'erLhcoreClassModelGalleryFiletype' );  
       
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
                
      $q->orderBy(isset($params['sort']) ? $params['sort'] : 'id ASC' ); 
              
      $objects = $session->find( $q );
                    
      return $objects; 
   }
   
   public static function getListCount($params = array())
   {
       $session = erLhcoreClassGallery::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_gallery_filetypes" );     
         
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
       
   public $extension = null;
   public $mime = '';
   public $content = '';
   public $player = ''; 
   
}

?>