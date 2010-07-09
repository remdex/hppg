<?php

$AlbumData = $Params['user_object'] ;
$imagePath = base64_decode($Params['user_parameters_unordered']['image']);

if (isset($Params['user_parameters_unordered']['image']) && file_exists($imagePath) && erLhcoreClassImageConverter::isPhotoLocal($imagePath))
{           	        
   try {
       $photoDir = dirname($imagePath);
       $fileName = basename($imagePath);
       
       if (!file_exists($photoDir.'/normal_'.$fileName) && !file_exists($photoDir.'/normal_'.$fileName))
       {      
       	
       	   $config = erConfigClassLhConfig::getInstance();
       		 
           $session = erLhcoreClassGallery::getSession();
           $image = new erLhcoreClassModelGalleryImage();
           $image->aid = $AlbumData->aid;       
            
           erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumbbig', $imagePath, $photoDir.'/normal_'.$fileName ); 
           erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumb', $imagePath, $photoDir.'/thumb_'.$fileName); 
           
           chmod($photoDir.'/normal_'.$fileName,$config->conf->getSetting( 'site', 'StorageFilePermissions' ));
	       chmod($photoDir.'/thumb_'.$fileName,$config->conf->getSetting( 'site', 'StorageFilePermissions' ));
           
           //Apply watermark if needed
           $dataWatermark = erLhcoreClassModelSystemConfig::fetch('watermark_data')->data;	    
              
	       // If watermark have to be applied we use conversion othwrwise just upload original to avoid any quality loose.
	       if ($dataWatermark['watermark_disabled'] == false && $dataWatermark['watermark_enabled_all'] == true) {	       	
            	erLhcoreClassImageConverter::getInstance()->converter->transform( 'jpeg', $imagePath, $imagePath ); 
            	chmod($imagePath,$config->conf->getSetting( 'site', 'StorageFilePermissions' ));
           }
                     
           $image->filesize = filesize($photoDir.'/'.$fileName);
           $image->total_filesize = filesize($photoDir.'/'.$fileName)+filesize($photoDir.'/thumb_'.$fileName)+filesize($photoDir.'/normal_'.$fileName);
           $image->filepath = str_replace('albums/','',$photoDir).'/';
                  
           $imageAnalyze = new ezcImageAnalyzer( $imagePath ); 	       
           $image->pwidth = $imageAnalyze->data->width;
           $image->pheight = $imageAnalyze->data->height;
           $image->hits = 0;
           $image->ctime = time();
           $image->owner_id = 0;
           $image->pic_rating = 0;
           $image->votes = 0;
           
           $image->title = '';
           $image->caption = '';
           $image->keywords =  '';
           $image->approved =  1;
           $image->filename = $fileName;
                  
           $session->save($image);
           $image->clearCache();       
       }
       
    } catch (Exception $e) {
        erLhcoreClassLog::write('Exception during upload'.$e);
        echo json_encode(array('result' => 'item'));
        exit;
        return ;
    } 
}
echo json_encode(array('result' => 'item'));

exit;
?>