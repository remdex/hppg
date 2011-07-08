<?php

$sessionID = $_POST['sessionupload'];

$session = erLhcoreClassGallery::getSession();

$q = $session->createFindQuery( 'erLhcoreClassModelGalleryUpload' );
$q->where( $q->expr->eq( 'hash', $q->bindValue( $sessionID ) ) )->limit( 1 );
$objects = $session->find( $q, 'erLhcoreClassModelGalleryUpload' ); 

if (count($objects) == 1)
{	
	if (isset($_FILES["Filedata"]) && is_uploaded_file($_FILES["Filedata"]["tmp_name"]) && $_FILES["Filedata"]["error"] == 0 && ($filetype = erLhcoreClassModelGalleryFiletype::isValid('Filedata')) !== false)
	{
	    
	    $fileSession = array_pop($objects);
	    
	    $image = new erLhcoreClassModelGalleryImage();
	    $image->aid = $fileSession->album_id;	
	    $session->save($image);
	    	        
	   try {
	    
	   	   $config = erConfigClassLhConfig::getInstance();
	   	
	   	   $photoDir = 'albums/userpics/'.date('Y').'y/'.date('m').'/'.date('d').'/'.$fileSession->user_id.'/'.$fileSession->album_id;
	   	   $photoDirPhoto = 'userpics/'.date('Y').'y/'.date('m').'/'.date('d').'/'.$fileSession->user_id.'/'.$fileSession->album_id.'/';
	   	   erLhcoreClassImageConverter::mkdirRecursive($photoDir);
	   	   $fileNamePhysic = erLhcoreClassImageConverter::sanitizeFileName($_FILES['Filedata']['name']);
	   	   
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
    	       'post_file_name'   => 'Filedata',
    	       'file_session'     => $fileSession
	       ));

	       $image->hits = 0;
	       $image->ctime = time();
	       $image->owner_id = $fileSession->user_id;
	       $image->pic_rating = 0;
	       $image->votes = 0;
	       
	       $image->title = $_POST['title'];
	       $image->caption = $_POST['description'];
	       $image->keywords =  $_POST['keyword'];
	       
	       $userOwner = erLhcoreClassUser::instance();
	       $userOwner->setLoggedUser($fileSession->user_id);	  
	       
           $canApproveSelfImages = $userOwner->hasAccessTo('lhgallery','auto_approve_self_photos');
           $canApproveAllImages =  $userOwner->hasAccessTo('lhgallery','auto_approve');           
           $canChangeApprovement = ($image->owner_id == $userOwner->getUserID() && $canApproveSelfImages) || ($canApproveAllImages == true);

	       $image->approved = $canChangeApprovement == true ? 1 : 0;
	       	       	       
	       $image->anaglyph =  (isset($_POST['anaglyph']) && $_POST['anaglyph'] == 'true') ? 1 : 0;
	      
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
	       
	    } catch (Exception $e) {
	        $session->delete($image);
	        erLhcoreClassLog::write('Exception during upload'.$e);
	        return ;
	    }  	    
	    
		
	} else {
	    erLhcoreClassLog::write('Upload failed: '.print_r($_FILES,true));
	}

} else {
	erLhcoreClassLog::write('Not found: '.$sessionID);
}


exit;

?>