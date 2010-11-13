<?php

class erLhcoreClassModelGalleryPendingConvert {
        
   public function getState()
   {
       return array(
               'pid'        => $this->pid,
               'status'     => $this->status            
       );
   }
   
   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   } 

   public static function addImage($pid) 
   {
   	   $db = ezcDbInstance::get();
       $stmt = $db->prepare('INSERT INTO lh_gallery_pending_convert VALUES (:pid,:status)');
       $stmt->bindValue( ':pid',$pid);       
       $stmt->bindValue( ':status',0);       
       $stmt->execute();
   }

   public function process()
   {
       if ($this->image !== false && $this->image->media_type == erLhcoreClassModelGalleryImage::mediaTypeSWF ){
        
            $command = erLhcoreClassModelSystemConfig::fetch('flash_screenshot_command')->current_value.' '.escapeshellarg($this->image->file_path_filesystem);

            $config = erConfigClassLhConfig::getInstance();

            $wwwUser = erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'default_www_user' );
   		    $wwwUserGroup = erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'default_www_group' );
   		            
            // Prepare to run ImageMagick command
            $descriptors = array( 
                array( 'pipe', 'r' ),
                array( 'pipe', 'w' ),
                array( 'pipe', 'w' ),
            );
                  
            // Open ImageMagick process
            $imageProcess = proc_open( $command, $descriptors, $pipes );
            // Close STDIN pipe
            fclose( $pipes[0] );
            
            $errorString  = '';
            $outputString = '';
            // Read STDERR 
            do 
            {
                $outputString .= rtrim( fgets( $pipes[1], 1024 ), "\n" );
                $errorString  .= rtrim( fgets( $pipes[2], 1024 ), "\n" );
            } while ( !feof( $pipes[2] ) );
            
            // Wait for process to terminate and store return value
            $status = proc_get_status( $imageProcess );
            while ( $status['running'] !== false )
            {
                // Sleep 1/100 second to wait for convert to exit
                usleep( 10000 );
                $status = proc_get_status( $imageProcess );
            }
            $return = proc_close( $imageProcess );
                           
            // Process potential errors
            // Exit code may be messed up with -1, especially on Windoze
            if ( ( $status['exitcode'] != 0 && $status['exitcode'] != -1 ) || strlen( $errorString ) > 0 )
            {      
                erLhcoreClassLog::write("Conversion SWF: The command '{$command}' resulted in an error ({$status['exitcode']}): '{$errorString}'. Output: '{$outputString}'");
                  
            } else {                
                $dir = dirname( $this->image->file_path_filesystem ); 
                                  
         	    erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumb','var/tmpfiles/screen_cropped.jpg', $dir.'/thumb_'.str_replace('.swf','.jpg',$this->image->filename) );
        	   
         	    chown($dir.'/thumb_'.str_replace('.swf','.jpg',$this->image->filename),$wwwUser);
        	    chgrp($dir.'/thumb_'.str_replace('.swf','.jpg',$this->image->filename),$wwwUserGroup);
        	    chmod($dir.'/thumb_'.str_replace('.swf','.jpg',$this->image->filename),$config->conf->getSetting( 'site', 'StorageFilePermissions' )); 
                
        	    $this->image->has_preview = 1;
        	    
        	    $session = erLhcoreClassGallery::getSession();
        	    $session->update($this->image);
        	    $this->image->clearCache();
        	    
        	    $this->removeThis();
            }
    	            
       }
       
        
        
   }
   
   public function __get($variable)
   {
   		switch ($variable) {
   			case 'image':
   				
   				try {
   					$this->image = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryImage', (int)$this->pid );
   				} catch (Exception $e) {
   					$this->image = false;
   				}
   				return $this->image;
   				break;
   		
   			default:
   				break;
   		}
   }
   
   public function removeThis()
   {       
       erLhcoreClassGallery::getSession()->delete($this); 
   }
   
   public $pid = 0;
   public $status = null;
   
}

?>