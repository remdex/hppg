<?php


class erLhcoreClassVideoConverter {
      
   public $converter;
   private static $instance = null;
   
   function __construct()
   {                   
                    
   }   

    public static function isVideo($file)
    { 
       if ($_FILES[$file]['error'] == 0)
       {
           try {                                                       
               if ($_FILES[$file]['size'] > 0)
               {                   
                   return true;                   
                   
               } else                
               return false;
               
           } catch (Exception $e) {
               return false;
           }
       
       } else {
           return false;
       } 
    }
    
    public static function handleUpload(& $image,$params = array())
    {
        $photoDir = $params['photo_dir'];
        $fileNamePhysic = $params['file_name_physic'];
        
        $config = erConfigClassLhConfig::getInstance();
        
       	rename($params['file_upload_path'],$photoDir.'/'.$fileNamePhysic);
               
        chmod($photoDir.'/'.$fileNamePhysic,$config->getSetting( 'site', 'StorageFilePermissions' ));
       
        $image->filesize = filesize($photoDir.'/'.$fileNamePhysic);
        $image->total_filesize = $image->filesize;
        $image->filepath = $params['photo_dir_photo'];
       
        $image->media_type = erLhcoreClassModelGalleryImage::mediaTypeVIDEO;
        
        $image->filename = $fileNamePhysic;
        
        erLhcoreClassModelGalleryPendingConvert::addImage($image->pid);
    }
    
    public static function handleUploadLocal(& $image,$params = array())
    {
        $photoDir = $params['photo_dir'];
        $fileNamePhysic = $params['file_name_physic'];
        $pathExtracted = $params['post_file_name'];
        $album = $params['album'];
        
        $config = erConfigClassLhConfig::getInstance();

        $wwwUser = erConfigClassLhConfig::getInstance()->getSetting( 'site', 'default_www_user' );
   		$wwwUserGroup = erConfigClassLhConfig::getInstance()->getSetting( 'site', 'default_www_group' );
        
		rename($pathExtracted,$photoDir.'/'.$fileNamePhysic);
		
    	chown($photoDir.'/'.$fileNamePhysic,$wwwUser);
    	chgrp($photoDir.'/'.$fileNamePhysic,$wwwUserGroup);
    	chmod($photoDir.'/'.$fileNamePhysic,$config->getSetting( 'site', 'StorageFilePermissions' ));
    	
    	$image->filesize = filesize($photoDir.'/'.$fileNamePhysic);
    	$image->total_filesize = $image->filesize;
    	$image->filepath = $params['photo_dir_photo'];
    	
        $image->media_type = erLhcoreClassModelGalleryImage::mediaTypeVIDEO;
                            
    	$image->hits = 0;
    	
    	erLhcoreClassModelGalleryPendingConvert::addImage($image->pid);
    }
    
    public static function handleUploadBatch(& $image,$params = array())
    {
        $photoDir = $params['photo_dir'];
        $fileNamePhysic = $params['file_name_physic'];
        $imagePath = $params['post_file_name'];
        
        $config = erConfigClassLhConfig::getInstance();
    	
    	$image->filesize = filesize($imagePath);
    	$image->total_filesize = $image->filesize;
    	    
        $image->media_type = erLhcoreClassModelGalleryImage::mediaTypeVIDEO;      
    	$image->hits = 0;
    	
    	erLhcoreClassModelGalleryPendingConvert::addImage($image->pid);
    } 
    
    public static function isVideoLocal($filePath)
    {              
           try { 
                                        
               if ( filesize($filePath) > 0)
               {                   
                   return true;                   
                   
               } else                
               return false;
               
           } catch (Exception $e) {
               return false;
           } 
    }
    
    // Borowed from coppermine gallery
    public static function sanitizeFileName($str)
    {  
       static $forbidden_chars;
      if (!is_array($forbidden_chars)) {
        $mb_utf8_regex = '[\xE1-\xEF][\x80-\xBF][\x80-\xBF]|\xE0[\xA0-\xBF][\x80-\xBF]|[\xC2-\xDF][\x80-\xBF]';
        if (function_exists('html_entity_decode')) {
          $chars = html_entity_decode('$/\\:*?&quot;&#39;&lt;&gt;|` &amp;', ENT_QUOTES, 'UTF-8');
        } else {
          $chars = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;', '&nbsp;', '&#39;'), array('&', '"', '<', '>', ' ', "'"), $CONFIG['forbiden_fname_char']);
        }
        preg_match_all("#$mb_utf8_regex".'|[\x00-\x7F]#', $chars, $forbidden_chars);
      }
      /**
       * $str may also come from $_POST, in this case, all &, ", etc will get replaced with entities.
       * Replace them back to normal chars so that the str_replace below can work.
       */
      $str = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $str);;
      $return = str_replace($forbidden_chars[0], '-', $str);
      $return = str_replace(array(')','('), array('',''), $return);
      $return = str_replace(' ', '-', $return);
    
      /**
      * Fix the obscure, misdocumented "feature" in Apache that causes the server
      * to process the last "valid" extension in the filename (rar exploit): replace all
      * dots in the filename except the last one with an underscore.
      */
      // This could be concatenated into a more efficient string later, keeping it in three
      // lines for better readability for now.
      $extension = strtolower(ltrim(substr($return,strrpos($return,'.')),'.'));
      $filenameWithoutExtension = str_replace('.' . $extension, '', $return);
      $return = str_replace('.', '-', $filenameWithoutExtension) .'.' . $extension;
      return $return;
    }
    
    public static function getExtension($fileName){
        return current(end(explode('.',$fileName)));
    }
 
}

?>