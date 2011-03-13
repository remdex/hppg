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
                  
           $session->save($image); 
           
           $filetype->processLocalBatch($image,array(
    	       'photo_dir'        => $photoDir,
    	       'file_name_physic' => $fileName,
    	       'post_file_name'   => $imagePath    	      
	       ));
           
           $image->filepath = str_replace('albums/','',$photoDir).'/';
                                 
           $image->hits = 0;
           $image->ctime = time();
           $image->owner_id = erLhcoreClassUser::instance()->getUserID();
           $image->pic_rating = 0;
           $image->votes = 0;
           
           $image->title = '';
           $image->caption = '';
           $image->keywords =  '';
           $image->approved =  1;
           $image->filename = $fileName;
                  
           $session->update($image);
           $image->clearCache();
           
           // Index colors
	       erLhcoreClassPalleteIndexImage::indexImage($image,true);  
	       
	       // Index face if needed
	       erLhcoreClassModelGalleryFaceData::indexImage($image,true);
	       
	       // Index in search table
	       erLhcoreClassModelGallerySphinxSearch::indexImage($image,true);
	       
	       
	       erLhcoreClassModelGalleryAlbum::updateAddTime($image);
	            
       }
       
    } catch (Exception $e) {
        $session->delete($image);
        erLhcoreClassLog::write('Exception during upload'.$e);
        echo json_encode(array('result' => 'item'));
        exit;
        return ;
    } 
}
echo json_encode(array('result' => 'item'));

exit;
?>