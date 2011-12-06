<?php

class erLhcoreClassModelGalleryImgSeekData {
    
   public static function removeImage($pid) {
       if (erConfigClassLhConfig::getInstance()->getSetting( 'imgseek', 'enabled' ) == true)
       {
           try {           
               $xmlRPCClient = self::getImgSeekClientInstance();                    
               $xmlRPCClient->execute( array (
                'op' => 'removeid',
                'dbid' => erConfigClassLhConfig::getInstance()->getSetting( 'imgseek', 'database_id' ),
                'id'   => $pid
               ));                     
           } catch (Exception $e) {
               
           }  
       }     
   }
   
   // Used only in cronjob
   public static function indexUnindexedImages($limit = 32)
   {           
        if (erConfigClassLhConfig::getInstance()->getSetting( 'imgseek', 'enabled' ) == true)
        {            
            if (($lastIndex = erLhcoreClassPalleteIndexImage::getLastIndex('imgseek_index')) == 0)
            {
                $lastIndex = 0; 
            }

            $imagesUnindexed = erLhcoreClassModelGalleryImage::getImages(array('limit' => $limit, 'sort' =>  'pid ASC','filtergt' => array('pid' => $lastIndex)));
            $lastIndexNew = $lastIndex;
            foreach ($imagesUnindexed as $image)
            {
                echo "Indexing imgseek image PID - ",$image->pid,"\n";
                self::indexImage($image);
                
                $lastIndexNew = $image->pid;
            }

            // Changed something
            if ($lastIndexNew != $lastIndex) {
                echo "Updating last indexed imgseek PID - ",$lastIndexNew,"\n";
                erLhcoreClassPalleteIndexImage::setLastIndex('imgseek_index',$lastIndexNew);
            }
        }
   }
   
   static private $imgSeekClient = NULL;
   
   static function getImgSeekClientInstance() {
        if (self::$imgSeekClient == NULL) {
            self::$imgSeekClient = new erLhcoreClassNodeImgSeek('http://'.erConfigClassLhConfig::getInstance()->getSetting( 'imgseek', 'host' ),erConfigClassLhConfig::getInstance()->getSetting( 'imgseek', 'port' ));
        }
        return self::$imgSeekClient;
   }
   
   /**
    * We cannot use __set options for image album because of findIterator, it does not reset's album internal variable.
    * 
    * */
   public static function indexImage($image,$checkDelay = false) {

       if (erConfigClassLhConfig::getInstance()->getSetting( 'imgseek', 'enabled' ) == true && $image->approved == 1 && ($checkDelay == false || ($checkDelay == true && erConfigClassLhConfig::getInstance()->getSetting( 'face_search', 'delay_index' ) == false))) {

           $photoPath = 'albums/'.$image->filepath.'normal_'. $image->filename;
           
           if ( ((erConfigClassLhConfig::getInstance()->getSetting('site','file_storage_backend') == 'filesystem' && file_exists($photoPath) && is_file($photoPath)) || erConfigClassLhConfig::getInstance()->getSetting('site','file_storage_backend') == 'amazons3') && $image->media_type == erLhcoreClassModelGalleryImage::mediaTypeIMAGE ) { 

               $deleteFile = false;
               if ( erConfigClassLhConfig::getInstance()->getSetting('site','file_storage_backend') == 'filesystem' ) {
                    $url = urlencode(erLhcoreClassSystem::instance()->SiteDir . $photoPath);
               } else { 
                    // FIXME
                    $urlRemote = erConfigClassLhConfig::getInstance()->getSetting('amazons3','endpoint') . '/albums/'.$image->filepath.'normal_'.$image->filename;
                    $url = 'var/tmpfiles/'.sha1('/albums/'.$image->filepath.'normal_'.$image->filename.time()).'.'.erLhcoreClassImageConverter::getExtension($image->filename);
                    file_put_contents($url,file_get_contents($urlRemote));
                    $deleteFile = true;
               }

               $xmlRPCClient = self::getImgSeekClientInstance();              
               $xmlRPCClient->execute(array('op' => 'addimage', 
                                            'id' => $image->pid,
                                            'fp' => $url,
                                            'dbid' => erConfigClassLhConfig::getInstance()->getSetting( 'imgseek', 'database_id' )));  
              if ($deleteFile == true)  { 
                unlink($url);
              }     
         }         
            
       } elseif ($image->approved == 0) {
           self::removeImage($image->pid);
       }
   }
   
   public $pid = null;
   public $data = '';   
   public $sphinx_data = '';
   
   static private $faceRestClient = NULL;

}


?>