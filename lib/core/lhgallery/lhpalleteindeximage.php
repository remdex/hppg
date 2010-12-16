<?php

class erLhcoreClassPalleteIndexImage {
           
    public static function indexUnindexedImages()
    {        
        if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'seach_by_color_enabled' ) == false) return ;
                       
        $db = ezcDbInstance::get(); 
        $stmt = $db->prepare("SELECT MAX(pid) as last_index_image FROM lh_gallery_pallete_images");
        $stmt->execute();
        
        $result = $stmt->fetchColumn(); 

        if ($result === false) $result = 0;
               
        $imagesUnindexed = erLhcoreClassModelGalleryImage::getImages(array('filtergt' => array('pid' => $result)));
                
        foreach ($imagesUnindexed as $image)
        {
            self::indexImage($image);
        }
    }
    
    public static function indexImage($image, $checkDelayIndex = false) {
         
        // Do not index not approved images, or in live mode if delay image update is enabled
        if ($image->approved == 0 || 
            erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'seach_by_color_enabled' ) == false || 
            ($checkDelayIndex == true && erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'delay_color_index' ) == true) ) return ;
                
            
        $photoPath = 'albums/'.$image->filepath.'thumb_'.$image->filename;
               
        if (file_exists($photoPath) && is_file($photoPath)) { 

            if ($image->media_type == erLhcoreClassModelGalleryImage::mediaTypeIMAGE) 
            {          
                     try {
                            $imgPath = $photoPath;
                            $img = false;
                         try {
                                $imageInfo = new ezcImageAnalyzer( $imgPath  );
                                switch ($imageInfo->mime) {
                                	case 'image/jpeg':     		
                                	      $img = imagecreatefromjpeg($imgPath);
                                		break;
                                		
                                	case 'image/gif':     		
                                	      $img = imagecreatefromgif($imgPath);
                                		break;
                                			
                                	case 'image/png':     		
                                	      $img = imagecreatefrompng($imgPath);
                                		break;
                                
                                	default:
                                	    
                                		break;
                                 }
                         } catch (Exception $e){
                               return 0;
                         }

                                                 
                         if ($img !== false)
                         {               
                             list($width,$height) = getimagesize($photoPath);	
                             
                             $db = ezcDbInstance::get(); 
                                                         
                    	     $stmt = $db->prepare("DELETE FROM lh_gallery_pallete_images WHERE pid = {$image->pid}");
                    	     $stmt->execute();
                             
                             for ($i = 1; $i < $width;$i++) {
                                for ($n = 1; $n < $height;$n++) {                        
                                    $thisColor = imagecolorat($img, $i, $n); 
                                    $rgb = imagecolorsforindex($img, $thisColor); 
                                    
                                    // Standard euclidean distance
                                    // $stmt = $db->prepare('SELECT id,POW(POW((:red-red),2)+POW((:blue - blue),2)+POW((:green-green),2),0.5) as distance FROM lh_gallery_pallete ORDER BY distance ASC LIMIT 1');
                                    
                                    // http://www.compuphase.com/cmetric.htm
                                    // More reliable version
                                    $stmt = $db->prepare('SELECT id,SQRT((2+((red+:red)/2)/256)*POW((:red-red),2) + 4*(POW((:green-green),2)) + (2+(255-((red+:red)/2))/256)*POW((:blue - blue),2) ) as distance FROM lh_gallery_pallete ORDER BY distance ASC LIMIT 1');
                                    
                                    $stmt->bindValue( ':red',$rgb['red']);
                                    $stmt->bindValue( ':blue',$rgb['blue']);
                                    $stmt->bindValue( ':green',$rgb['green']);
                                    $stmt->execute();
                                    $pallete_id = $stmt->fetchColumn(); 
                                    
                            	    $stmt = $db->prepare("INSERT INTO lh_gallery_pallete_images (pid,pallete_id,count) VALUES ({$image->pid},$pallete_id,1) ON DUPLICATE KEY UPDATE count = count + 1");
                            	    $stmt->execute();
                                }
                             }
                         }
                     } catch (Exception $e){ 
                         
                     }
                                                   
            }
        }               
    }
    
    
    public static function removeFromIndex($pid){ 
        $db = ezcDbInstance::get(); 
        $stmt = $db->prepare("DELETE FROM lh_gallery_pallete_images WHERE pid = {$pid}");
        $stmt->execute();
    }
}