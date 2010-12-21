<?php

class erLhcoreClassPalleteIndexImage {
           
    // Used only in cronjob
    public static function indexUnindexedImages()
    {        
        if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'search_enabled' ) == false) return ;
                
        if (($lastIndex = self::getLastIndex('image_index')) == 0)
        {
            $db = ezcDbInstance::get(); 
            $stmt = $db->prepare("SELECT MAX(pid) as last_index_image FROM lh_gallery_pallete_images");
            $stmt->execute();        
            $lastIndex = (int)$stmt->fetchColumn(); 
        }       
                
        $imagesUnindexed = erLhcoreClassModelGalleryImage::getImages(array('sort' =>  'pid ASC','filtergt' => array('pid' => $lastIndex)));
                
        $lastIndexNew = $lastIndex;
        foreach ($imagesUnindexed as $image)
        {
            echo "Indexing color image PID - ",$image->pid,"\n";
            self::indexImage($image);
            
            // We update sphinx index if necessary
            erLhcoreClassModelGallerySphinxSearch::updateColorAttribute($image);
            
            $lastIndexNew = $image->pid;
        }
        
        // Changed something
        if ($lastIndexNew != $lastIndex) {
            echo "Updating last indexed color PID - ",$lastIndexNew,"\n";
            self::setLastIndex('image_index',$lastIndexNew);
        }
           
             
    }
    
    public static function setLastIndex($identifier,$value)
    {
        $db = ezcDbInstance::get(); 
        
        $stmt = $db->prepare('UPDATE lh_gallery_last_index SET `value` = :value WHERE identifier = :identifier');
        $stmt->bindValue( ':identifier',$identifier);      
        $stmt->bindValue( ':value',$value);      
        $stmt->execute();
    }
    
    public static function getLastIndex($identifier)
    {
        $db = ezcDbInstance::get(); 
        
        $stmt = $db->prepare('SELECT `value` FROM lh_gallery_last_index WHERE identifier = :identifier');
        $stmt->bindValue( ':identifier',$identifier);      
        $stmt->execute();
        $value = (int)$stmt->fetchColumn(); 
        
        return $value;  
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
                                                 	                         	     
                    	     $data_array = array();
                    	     
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
                                 self::storePalleteStats($image->pid,$data_array);
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
    
    public static function storePalleteStats($pid,$stats = false)
    {
        /**
         * In first to as have been passed current image stats in other case no stats passed, and we have to fetch then manualy
         * 
         * */
        $statsImploded = '';
        if (is_array($stats)) {
           arsort($stats);           
           if (count($stats) > 10) {
                $stats = array_slice(array_keys($stats),0,10);
           } else {
               $stats = array_keys($stats);
           }
           $statsImploded = implode(',',$stats);
        } else {            
            $db = ezcDbInstance::get(); 
            $stmt = $db->prepare('SELECT pallete_id FROM lh_gallery_pallete_images WHERE pid = :pid ORDER BY count DESC LIMIT 10');
            $stmt->bindValue( ':pid',$pid);            
            $stmt->execute();            
            $stats = $stmt->fetchAll(PDO::FETCH_COLUMN,0);  
            $statsImploded = implode(',',$stats);                           
        }
                
        if (trim($statsImploded) != ''){
            $db = ezcDbInstance::get(); 
            $stmt = $db->prepare('REPLACE INTO lh_gallery_pallete_images_stats (pid,colors) VALUES (:pid,:colors);');
            $stmt->bindValue( ':pid',$pid);            
            $stmt->bindValue( ':colors',$statsImploded);            
            $stmt->execute();
        }
        
    }
    
    public static function removeFromIndex($pid){ 
        $db = ezcDbInstance::get(); 
        $stmt = $db->prepare("DELETE FROM lh_gallery_pallete_images WHERE pid = {$pid}");
        $stmt->execute();
        
        $stmt = $db->prepare("DELETE FROM lh_gallery_pallete_images_stats WHERE pid = {$pid}");
        $stmt->execute();
    }
}