<?php

$uploader = new qqFileUploader(explode(',',str_replace('\'','',erLhcoreClassModelSystemConfig::fetch('allowed_file_types')->current_value)), erLhcoreClassModelSystemConfig::fetch('max_photo_size')->current_value*1024);
$result = $uploader->handleUpload('var/tmpfiles/');
$currentUser = erLhcoreClassUser::instance();
 
if ( ($albumto = erLhcoreClassModelGalleryAlbum::canUpload($uploader->getParam('album_id'),$currentUser->hasAccessTo('lhgallery','administrate')) ) !== false ){

	if (isset($result['success']) && $result['success'] == 'true' && ($filetype = erLhcoreClassModelGalleryFiletype::isValidLocal($uploader->getFilePath())) !== false)
	{
	    $result['filepath'] = $uploader->getFilePath();  
        $result['filename'] = $uploader->getFileName();
        $result['filename_user'] = $uploader->getUserFileName();

        $db = ezcDbInstance::get();
        $db->beginTransaction();
    
        $session = erLhcoreClassGallery::getSession();
        
        if ($currentUser->isLogged())
            $user_id = $currentUser->getUserID();	
        else 
            $user_id = erConfigClassLhConfig::getInstance()->conf->getSetting( 'user_settings', 'anonymous_user_id' );	

	   $image = new erLhcoreClassModelGalleryImage();
	   $image->aid = $albumto->aid;	
	   $session->save($image);

	   try {

	   	   $config = erConfigClassLhConfig::getInstance();

	   	   $photoDir = 'albums/userpics/'.date('Y').'y/'.date('m').'/'.date('d').'/'.$user_id.'/'.$albumto->aid;
	   	   $photoDirPhoto = 'userpics/'.date('Y').'y/'.date('m').'/'.date('d').'/'.$user_id.'/'.$albumto->aid.'/';
	   	   erLhcoreClassImageConverter::mkdirRecursive($photoDir);
	   	   $fileNamePhysic = erLhcoreClassImageConverter::sanitizeFileName($result['filename_user']);

	   	   if ($config->conf->getSetting( 'site', 'file_storage_backend' ) == 'filesystem')
           {    	       
    	       if (file_exists($photoDir.'/'.$fileNamePhysic)) {
    	       		$fileNamePhysic = erLhcoreClassModelForgotPassword::randomPassword(5).time().'-'.$fileNamePhysic;
    	       }
           }

	       $filetype->process($image,array(
    	       'photo_dir'        => $photoDir,
    	       'photo_dir_photo'  => $photoDirPhoto,
    	       'file_name_physic' => $fileNamePhysic,
    	       'file_upload_path' => $result['filepath']
	       ));

	       $image->hits = 0;
	       $image->ctime = time();
	       $image->owner_id = $user_id;
	       $image->pic_rating = 0;
	       $image->votes = 0;
	       
	       $image->title = $uploader->getParam('title');
	       $image->caption = $uploader->getParam('description');
	       $image->keywords =  $uploader->getParam('keywords');
	       
           $canApproveSelfImages = $currentUser->hasAccessTo('lhgallery','auto_approve_self_photos');
           $canApproveAllImages =  $currentUser->hasAccessTo('lhgallery','auto_approve');           
           $canChangeApprovement = ($image->owner_id == $currentUser->getUserID() && $canApproveSelfImages) || ($canApproveAllImages == true);

	       $image->approved = $canChangeApprovement == true ? 1 : 0;
	       	       	       
	       $image->anaglyph =  ($uploader->getParam('anaglyph') == 'true') ? 1 : 0;
	      
	       $session->update($image);
	       $image->clearCache();
           
	       // Index colors
	       erLhcoreClassPalleteIndexImage::indexImage($image,true);
           
	       // Index face if needed
	       erLhcoreClassModelGalleryFaceData::indexImage($image,true);
	       
	       // Index in search table
	       erLhcoreClassModelGallerySphinxSearch::indexImage($image,true);
	       
	       // Expires last uploads shard index
	       erLhcoreClassGallery::expireShardIndexByIdentifier(array('last_uploads','last_commented'));
	              
	       erLhcoreClassModelGalleryAlbum::updateAddTime($image);
	       
	       echo json_encode(array('success' => 'true'));	       
	    } catch (Exception $e) {
	        $session->delete($image);
	        erLhcoreClassLog::write('Exception during upload'.$e);
	        return ;
	    }  	    
	    
		$db->commit();
		 
	} else {
	    erLhcoreClassLog::write('Upload failed: '.print_r($_FILES,true));
	}
}


exit;

?>