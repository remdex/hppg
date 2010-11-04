<?php

$AlbumData = $Params['user_object'] ;
$imagePath = base64_decode($Params['user_parameters_unordered']['image']);

if (isset($Params['user_parameters_unordered']['image']) && file_exists($imagePath) && ($filetype = erLhcoreClassModelGalleryFiletype::isValidLocal($imagePath)) !== false )
{           	        
   try {
       $photoDir = dirname($imagePath);
       $fileName = basename($imagePath);
       
       if (!file_exists($photoDir.'/normal_'.$fileName) && !file_exists($photoDir.'/thumb_'.$fileName))
       {      
       	
       	   $config = erConfigClassLhConfig::getInstance();
       		 
           $session = erLhcoreClassGallery::getSession();
           $image = new erLhcoreClassModelGalleryImage();
           $image->aid = $AlbumData->aid;       
           
           
           $filetype->processLocalBatch($image,array(
    	       'photo_dir'        => $photoDir,
    	       'file_name_physic' => $fileName,
    	       'post_file_name'   => $imagePath    	      
	       ));
           
           $image->filepath = str_replace('albums/','',$photoDir).'/';
           
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