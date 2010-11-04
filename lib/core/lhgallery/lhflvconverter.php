<?php


class erLhcoreClassFLVConverter {
      
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
               
               if(!extension_loaded('ffmpeg')) {
                    return false;
               }

               $movie = new ffmpeg_movie( $_FILES[$file]['tmp_name'] );
               
               if (!$movie->hasVideo() || $_FILES[$file]['size'] > ((int)erLhcoreClassModelSystemConfig::fetch('max_photo_size')->current_value*1024) ){
                   return false; // Something wrong
               }
                              
               return true;   
               
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
        $fileSession = $params['file_session'];
        
        $config = erConfigClassLhConfig::getInstance();
        
       	move_uploaded_file($_FILES[$params['post_file_name']]["tmp_name"],$photoDir.'/'.$fileNamePhysic);
        
        chmod($photoDir.'/'.$fileNamePhysic,$config->conf->getSetting( 'site', 'StorageFilePermissions' ));
       
        $image->filesize = filesize($photoDir.'/'.$fileNamePhysic);
        $image->total_filesize = $image->filesize;
        $image->filepath = 'userpics/'.$fileSession->user_id.'/'.$fileSession->album_id.'/'; 
                
        $movie = new ffmpeg_movie( $photoDir.'/'.$fileNamePhysic ); 
                      
        $image->pwidth = $movie->getFrameWidth();
        $image->pheight = $movie->getFrameHeight();
        
        $image->media_type = erLhcoreClassModelGalleryImage::mediaTypeFLV;

        // Make some screenshot
        $frame = $movie->getFrame( $movie->getFrameCount()/2 );

        $imageFrame = $frame->toGDImage();

        $image->has_preview = 1;

        $parts = explode('.',$fileNamePhysic);
        array_pop($parts);
        
        imagejpeg($imageFrame,$photoDir.'/original_'.implode('.',$parts).'.jpg');
                                
        erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumbbig', $photoDir.'/original_'.implode('.',$parts).'.jpg', $photoDir.'/normal_'.implode('.',$parts).'.jpg' ); 
        erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumb', $photoDir.'/original_'.implode('.',$parts).'.jpg', $photoDir.'/thumb_'.implode('.',$parts).'.jpg' ); 
       	       
        chmod($photoDir.'/normal_'.implode('.',$parts).'.jpg',$config->conf->getSetting( 'site', 'StorageFilePermissions' ));
        chmod($photoDir.'/thumb_'.implode('.',$parts).'.jpg',$config->conf->getSetting( 'site', 'StorageFilePermissions' ));
        
        unlink($photoDir.'/original_'.implode('.',$parts).'.jpg');    // Delete original screenshot               
        
    }
    
    // Handles uploads from archive, cronjob mode
    public static function handleUploadLocal(& $image,$params = array())
    {
        $photoDir = $params['photo_dir'];
        $fileNamePhysic = $params['file_name_physic'];
        $fileSession = $params['file_session'];
        $pathExtracted = $params['post_file_name'];
        $album = $params['album'];
        
        $config = erConfigClassLhConfig::getInstance();

        $wwwUser = erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'default_www_user' );
   		$wwwUserGroup = erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'default_www_group' );
   		 
		rename($pathExtracted,$photoDir.'/'.$fileNamePhysic);
    	chown($photoDir.'/'.$fileNamePhysic,$wwwUser);
    	chgrp($photoDir.'/'.$fileNamePhysic,$wwwUserGroup);
    	chmod($photoDir.'/'.$fileNamePhysic,$config->conf->getSetting( 'site', 'StorageFilePermissions' ));
    	 
    	$image->filesize = filesize($photoDir.'/'.$fileNamePhysic);
    	$image->total_filesize = $image->filesize;
    	$image->filepath = 'userpics/'.$fileSession->user_id.'/'.$album->aid.'/';
    	
    	$movie = new ffmpeg_movie( $photoDir.'/'.$fileNamePhysic ); 
    	$frame = $movie->getFrame( $movie->getFrameCount()/2 );

        $imageFrame = $frame->toGDImage();
        
        $image->pwidth = $movie->getFrameWidth();
        $image->pheight = $movie->getFrameHeight();
        
        $image->media_type = erLhcoreClassModelGalleryImage::mediaTypeFLV;
                
        $image->has_preview = 1;

        $parts = explode('.',$fileNamePhysic);
        array_pop($parts);
        
        imagejpeg($imageFrame,$photoDir.'/original_'.implode('.',$parts).'.jpg');
        
        erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumbbig', $photoDir.'/original_'.implode('.',$parts).'.jpg', $photoDir.'/normal_'.implode('.',$parts).'.jpg' ); 
        erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumb', $photoDir.'/original_'.implode('.',$parts).'.jpg', $photoDir.'/thumb_'.implode('.',$parts).'.jpg' ); 

        chown($photoDir.'/normal_'.implode('.',$parts).'.jpg',$wwwUser);
        chgrp($photoDir.'/normal_'.implode('.',$parts).'.jpg',$wwwUserGroup);       
        chmod($photoDir.'/normal_'.implode('.',$parts).'.jpg',$config->conf->getSetting( 'site', 'StorageFilePermissions' ));
        
        chown($photoDir.'/thumb_'.implode('.',$parts).'.jpg',$wwwUser);
        chgrp($photoDir.'/thumb_'.implode('.',$parts).'.jpg',$wwwUserGroup);     	
        chmod($photoDir.'/thumb_'.implode('.',$parts).'.jpg',$config->conf->getSetting( 'site', 'StorageFilePermissions' ));
                  
        unlink($photoDir.'/original_'.implode('.',$parts).'.jpg');    // Delete original screenshot
    	
    }
    
    public static function handleUploadBatch(& $image,$params = array())
    {
        $photoDir = $params['photo_dir'];
        $fileNamePhysic = $params['file_name_physic'];
        $imagePath = $params['post_file_name'];
        
        $config = erConfigClassLhConfig::getInstance();
              
        $image->filesize = filesize($imagePath);
        $image->total_filesize = $image->filesize;
                       
        $image->media_type = erLhcoreClassModelGalleryImage::mediaTypeFLV;
        
        $movie = new ffmpeg_movie( $imagePath ); 
                      
        $image->pwidth = $movie->getFrameWidth();
        $image->pheight = $movie->getFrameHeight();
        
        $image->media_type = erLhcoreClassModelGalleryImage::mediaTypeFLV;

        // Make some screenshot
        $frame = $movie->getFrame( $movie->getFrameCount()/2 );

        $imageFrame = $frame->toGDImage();

        $image->has_preview = 1;

        $parts = explode('.',$fileNamePhysic);
        array_pop($parts);
        
        imagejpeg($imageFrame,$photoDir.'/original_'.implode('.',$parts).'.jpg');
                                
        erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumbbig', $photoDir.'/original_'.implode('.',$parts).'.jpg', $photoDir.'/normal_'.implode('.',$parts).'.jpg' ); 
        erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumb', $photoDir.'/original_'.implode('.',$parts).'.jpg', $photoDir.'/thumb_'.implode('.',$parts).'.jpg' ); 
       	       
        chmod($photoDir.'/normal_'.implode('.',$parts).'.jpg',$config->conf->getSetting( 'site', 'StorageFilePermissions' ));
        chmod($photoDir.'/thumb_'.implode('.',$parts).'.jpg',$config->conf->getSetting( 'site', 'StorageFilePermissions' ));
        
        unlink($photoDir.'/original_'.implode('.',$parts).'.jpg');    // Delete original screenshot        
    }
     
    public static function isVideoLocal($filePath)
    {              
           try {
               
               if(!extension_loaded('ffmpeg')) {
                    return false;
               }

               $movie = new ffmpeg_movie( $filePath );
               
               if (!$movie->hasVideo() || filesize($filePath) > ((int)erLhcoreClassModelSystemConfig::fetch('max_photo_size')->current_value*1024) ){
                   return false; // Something wrong
               }
                              
               return true; 
                  
               
           } catch (Exception $e) {
               return false;
           } 
    }       
 
}

?>