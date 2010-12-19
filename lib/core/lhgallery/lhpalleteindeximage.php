<?php

class erLhcoreClassPalleteIndexImage {
           
    public static function indexUnindexedImages()
    {        
        if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'search_enabled' ) == false) return ;
                       
        $db = ezcDbInstance::get(); 
        $stmt = $db->prepare("SELECT MAX(pid) as last_index_image FROM lh_gallery_pallete_images");
        $stmt->execute();
        
        $result = (int)$stmt->fetchColumn(); 
                       
        $imagesUnindexed = erLhcoreClassModelGalleryImage::getImages(array('sort' =>  'pid ASC','filtergt' => array('pid' => $result)));
                
        foreach ($imagesUnindexed as $image)
        {
            echo "Indexing image PID - ",$image->pid,"\n";
            self::indexImage($image);
        }
    }
    
    public static function indexImage($image, $checkDelayIndex = false) {
         
        // Do not index not approved images, or in live mode if delay image update is enabled
        if ($image->approved == 0 || 
            erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'search_enabled' ) == false || 
            ($checkDelayIndex == true && erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'delay_index' ) == true) ) return ;
          
        /**
         * Minimum number of times pallete color must be matched to get record.
         * */ 
        $matchTreshold = erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'minimum_color_match' );  
            
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
                             
                    	     $cache = CSCacheAPC::getMem(); 
                    	     
                    	     $data_array = array();
                    	     
                             for ($i = 1; $i < $width;$i++) {
                                for ($n = 1; $n < $height;$n++) {                        
                                    $thisColor = imagecolorat($img, $i, $n); 
                                    $rgb = imagecolorsforindex($img, $thisColor); 
                                    
                                    // Standard euclidean distance
                                    // $stmt = $db->prepare('SELECT id,POW(POW((:red-red),2)+POW((:blue - blue),2)+POW((:green-green),2),0.5) as distance FROM lh_gallery_pallete ORDER BY distance ASC LIMIT 1');
                                    
                                    // http://www.compuphase.com/cmetric.htm
                                    // More reliable version                                    
                                    $cacheKey = 'color_pallete_red_'.$rgb['red'].'_blue_'.$rgb['blue'].'_green_'.$rgb['green'];
                                    
                                    if (($pallete_id = $cache->restore($cacheKey)) === false)
                                    {
                                        $stmt = $db->prepare('SELECT id,SQRT((2+((red+:red)/2)/256)*POW((:red-red),2) + 4*(POW((:green-green),2)) + (2+(255-((red+:red)/2))/256)*POW((:blue - blue),2) ) as distance FROM lh_gallery_pallete ORDER BY distance ASC LIMIT 1');
                                        $stmt->bindValue( ':red',$rgb['red']);
                                        $stmt->bindValue( ':blue',$rgb['blue']);
                                        $stmt->bindValue( ':green',$rgb['green']);
                                        $stmt->execute();
                                        $pallete_id = $stmt->fetchColumn();                                         
                                        $cache->store($cacheKey,$pallete_id,30*60); // Cache stored just for 30 minits
                                    }
                                    
                                    if (isset($data_array[$pallete_id])) {
                                        $data_array[$pallete_id] = $data_array[$pallete_id] + 1;
                                    } else {
                                        $data_array[$pallete_id] = 1;
                                    }
                                }
                             }
                                                                       
                             $valuesParts = array();
                             foreach ($data_array as $pallete => $count)
                             {
                                 if ($count > $matchTreshold)
                                 $valuesParts[] = "({$image->pid},{$pallete},$count)";
                             }
                             
                             
                             if (count($valuesParts) > 0) {
                                 $sql = 'REPLACE INTO lh_gallery_pallete_images VALUES '.implode(',',$valuesParts).';'; 
                                 $stmt = $db->prepare($sql);
                                 $stmt->execute();
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