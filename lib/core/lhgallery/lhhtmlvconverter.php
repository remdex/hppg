<?php


class erLhcoreClassHTMLVConverter {
      
   public $converter;
   private static $instance = null;
   
   function __construct()
   {                      
                    
   }
      
   public static function getInstance()  
   {
        if ( is_null( self::$instance ) )
        {          
            self::$instance = new erLhcoreClassHTMLVConverter();            
        }
        return self::$instance;
   }
    
   public static function isVideo($file)
   { 
       if ($_FILES[$file]['error'] == 0)
       {       
           try {
               
               $tag = new erLhcoreClassOgg( $_FILES[$file]['tmp_name']);
                
               if ($tag->LastError || $_FILES[$file]['size'] > ((int)erLhcoreClassModelSystemConfig::fetch('max_photo_size')->current_value*1024) ){
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
        
        $config = erConfigClassLhConfig::getInstance();
        
        if ($config->getSetting( 'site', 'file_storage_backend' ) == 'filesystem')
        {
           	rename($params['file_upload_path'],$photoDir.'/'.$fileNamePhysic);
            
            chmod($photoDir.'/'.$fileNamePhysic,$config->getSetting( 'site', 'StorageFilePermissions' ));
           
            $image->filesize = filesize($photoDir.'/'.$fileNamePhysic);
            $image->total_filesize = $image->filesize;
            $image->filepath = $params['photo_dir_photo']; 
    
            $tag = new erLhcoreClassOgg( $photoDir.'/'.$fileNamePhysic);
                   
            $image->pwidth = $tag->Streams['theora']['width'];
            $image->pheight = $tag->Streams['theora']['height'];
            
            $image->media_type = erLhcoreClassModelGalleryImage::mediaTypeHTMLV;
            
            if ($tag->Streams['picturable']) {
                $image->has_preview = 1;
                
                $parts = explode('.',$fileNamePhysic);
                array_pop($parts);
                
                if (isset($tag->Streams['theora']['framecount'])) {                
                   $tag->GetPicture(round($tag->Streams['theora']['framecount']/2),$photoDir.'/original_'.implode('.',$parts).'.jpg');
                } else {
                   $tag->GetPicture(1,$photoDir.'/original_'.implode('.',$parts).'.jpg');  
                }
                
                erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumbbig', $photoDir.'/original_'.implode('.',$parts).'.jpg', $photoDir.'/normal_'.implode('.',$parts).'.jpg' ); 
                erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumb', $photoDir.'/original_'.implode('.',$parts).'.jpg', $photoDir.'/thumb_'.implode('.',$parts).'.jpg' ); 
               	       
                chmod($photoDir.'/normal_'.implode('.',$parts).'.jpg',$config->getSetting( 'site', 'StorageFilePermissions' ));
                chmod($photoDir.'/thumb_'.implode('.',$parts).'.jpg',$config->getSetting( 'site', 'StorageFilePermissions' ));
                
                unlink($photoDir.'/original_'.implode('.',$parts).'.jpg');    // Delete original screenshot
            }
            
            $image->filename = $fileNamePhysic;
            
        } elseif ($config->getSetting( 'site', 'file_storage_backend' ) == 'amazons3') {
            
            $fileNamePhysic = erLhcoreClassModelForgotPassword::randomPassword(5).time().$fileNamePhysic;
            rename($params['file_upload_path'],'var/tmpupload/'.$fileNamePhysic);
            
            $image->filesize = filesize('var/tmpupload/'.$fileNamePhysic);
            $image->total_filesize = $image->filesize;
            $image->filepath = $params['photo_dir_photo']; 
    
            $tag = new erLhcoreClassOgg( 'var/tmpupload/'.$fileNamePhysic);
                   
            $image->pwidth = $tag->Streams['theora']['width'];
            $image->pheight = $tag->Streams['theora']['height'];
            
            $image->media_type = erLhcoreClassModelGalleryImage::mediaTypeHTMLV;
            
            S3::setAuth($config->getSetting( 'amazons3', 'aws_access_key' ), $config->getSetting( 'amazons3', 'aws_secret_key')); 
            
            if ($tag->Streams['picturable']) {
                $image->has_preview = 1;
                
                $parts = explode('.',$fileNamePhysic);
                array_pop($parts);
                
                if (isset($tag->Streams['theora']['framecount'])) {                
                   $tag->GetPicture(round($tag->Streams['theora']['framecount']/2),'var/tmpupload/original_'.implode('.',$parts).'.jpg');
                } else {
                   $tag->GetPicture(1,'var/tmpupload/original_'.implode('.',$parts).'.jpg');  
                }
                
                erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumbbig', 'var/tmpupload/original_'.implode('.',$parts).'.jpg','var/tmpupload/normal_'.implode('.',$parts).'.jpg' ); 
                erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumb', 'var/tmpupload/original_'.implode('.',$parts).'.jpg', 'var/tmpupload/thumb_'.implode('.',$parts).'.jpg' ); 

                S3::putObject(S3::inputFile('var/tmpupload/thumb_'.implode('.',$parts).'.jpg', false), $config->getSetting( 'amazons3', 'bucket' ), $photoDir . '/thumb_'.implode('.',$parts).'.jpg', S3::ACL_PUBLIC_READ);
                S3::putObject(S3::inputFile('var/tmpupload/normal_'.implode('.',$parts).'.jpg', false), $config->getSetting( 'amazons3', 'bucket' ), $photoDir . '/normal_'.implode('.',$parts).'.jpg', S3::ACL_PUBLIC_READ);
                
                unlink('var/tmpupload/normal_'.implode('.',$parts).'.jpg');    // Delete original screenshot
                unlink('var/tmpupload/thumb_'.implode('.',$parts).'.jpg');    // Delete original screenshot
            }
         
            S3::putObject(S3::inputFile('var/tmpupload/'.$fileNamePhysic, false), $config->getSetting( 'amazons3', 'bucket' ), $photoDir . '/' . $fileNamePhysic, S3::ACL_PUBLIC_READ);
                
            unlink('var/tmpupload/original_'.implode('.',$parts).'.jpg');    // Delete original screenshot
            unlink('var/tmpupload/'.$fileNamePhysic);    // Delete original screenshot
            $image->filename = $fileNamePhysic;
        }
    }
    
    // Handles uploads from archive, cronjob mode
    public static function handleUploadLocal(& $image,$params = array())
    {
        $photoDir = $params['photo_dir'];
        $fileNamePhysic = $params['file_name_physic'];
        $pathExtracted = $params['post_file_name'];
        $album = $params['album'];
        
        $config = erConfigClassLhConfig::getInstance();

        if ($config->getSetting( 'site', 'file_storage_backend' ) == 'filesystem')
        {
            $wwwUser = erConfigClassLhConfig::getInstance()->getSetting( 'site', 'default_www_user' );
       		$wwwUserGroup = erConfigClassLhConfig::getInstance()->getSetting( 'site', 'default_www_group' );
       		 
    		rename($pathExtracted,$photoDir.'/'.$fileNamePhysic);
        	chown($photoDir.'/'.$fileNamePhysic,$wwwUser);
        	chgrp($photoDir.'/'.$fileNamePhysic,$wwwUserGroup);
        	chmod($photoDir.'/'.$fileNamePhysic,$config->getSetting( 'site', 'StorageFilePermissions' ));
        	 
        	$image->filesize = filesize($photoDir.'/'.$fileNamePhysic);
        	$image->total_filesize = $image->filesize;
        	$image->filepath = $params['photo_dir_photo'];
    
        	$tag = new erLhcoreClassOgg( $photoDir.'/'.$fileNamePhysic);
                   
            $image->pwidth = $tag->Streams['theora']['width'];
            $image->pheight = $tag->Streams['theora']['height'];
            
            $image->media_type = erLhcoreClassModelGalleryImage::mediaTypeHTMLV;
            
            if ($tag->Streams['picturable']) {
                $image->has_preview = 1;
                
                $parts = explode('.',$fileNamePhysic);
                array_pop($parts);
                
                if (isset($tag->Streams['theora']['framecount'])) {                
                   $tag->GetPicture(round($tag->Streams['theora']['framecount']/2),$photoDir.'/original_'.implode('.',$parts).'.jpg');
                } else {
                   $tag->GetPicture(1,$photoDir.'/original_'.implode('.',$parts).'.jpg');  
                }
                
                erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumbbig', $photoDir.'/original_'.implode('.',$parts).'.jpg', $photoDir.'/normal_'.implode('.',$parts).'.jpg' ); 
                erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumb', $photoDir.'/original_'.implode('.',$parts).'.jpg', $photoDir.'/thumb_'.implode('.',$parts).'.jpg' ); 
    
                chown($photoDir.'/normal_'.implode('.',$parts).'.jpg',$wwwUser);
                chgrp($photoDir.'/normal_'.implode('.',$parts).'.jpg',$wwwUserGroup);       
                chmod($photoDir.'/normal_'.implode('.',$parts).'.jpg',$config->getSetting( 'site', 'StorageFilePermissions' ));
                
                chown($photoDir.'/thumb_'.implode('.',$parts).'.jpg',$wwwUser);
                chgrp($photoDir.'/thumb_'.implode('.',$parts).'.jpg',$wwwUserGroup);     	
                chmod($photoDir.'/thumb_'.implode('.',$parts).'.jpg',$config->getSetting( 'site', 'StorageFilePermissions' ));
                          
                unlink($photoDir.'/original_'.implode('.',$parts).'.jpg');    // Delete original screenshot
            }        
        } elseif ($config->getSetting( 'site', 'file_storage_backend' ) == 'amazons3') { 
        
            
        }
    }
    
    public static function handleUploadBatch(& $image,$params = array())
    {
        $photoDir = $params['photo_dir'];
        $fileNamePhysic = $params['file_name_physic'];
        $imagePath = $params['post_file_name'];
        
        $config = erConfigClassLhConfig::getInstance();
              
        $image->filesize = filesize($imagePath);
        $image->total_filesize = $image->filesize;
       
        $tag = new erLhcoreClassOgg( $imagePath );
               
        $image->pwidth = $tag->Streams['theora']['width'];
        $image->pheight = $tag->Streams['theora']['height'];
        
        $image->media_type = erLhcoreClassModelGalleryImage::mediaTypeHTMLV;
        
        if ($tag->Streams['picturable']) {
            $image->has_preview = 1;
            
            $parts = explode('.',$fileNamePhysic);
            array_pop($parts);
            
            if (isset($tag->Streams['theora']['framecount'])) {                
               $tag->GetPicture(round($tag->Streams['theora']['framecount']/2),$photoDir.'/original_'.implode('.',$parts).'.jpg');
            } else {
               $tag->GetPicture(1,$photoDir.'/original_'.implode('.',$parts).'.jpg');  
            }
            
            erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumbbig', $photoDir.'/original_'.implode('.',$parts).'.jpg', $photoDir.'/normal_'.implode('.',$parts).'.jpg' ); 
            erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumb', $photoDir.'/original_'.implode('.',$parts).'.jpg', $photoDir.'/thumb_'.implode('.',$parts).'.jpg' ); 
           	       
            chmod($photoDir.'/normal_'.implode('.',$parts).'.jpg',$config->getSetting( 'site', 'StorageFilePermissions' ));
            chmod($photoDir.'/thumb_'.implode('.',$parts).'.jpg',$config->getSetting( 'site', 'StorageFilePermissions' ));
            
            unlink($photoDir.'/original_'.implode('.',$parts).'.jpg');    // Delete original screenshot
        }        
        
    }
     
    public static function isVideoLocal($filePAth)
    {              
           try {
               
               $tag = new erLhcoreClassOgg( $filePAth );
                
               if ($tag->LastError || filesize($filePAth) > ((int)erLhcoreClassModelSystemConfig::fetch('max_photo_size')->current_value*1024)){
                   return false; // Something wrong
               }
                              
               return true;   
               
           } catch (Exception $e) {
               return false;
           } 
    }       
 
}

?>