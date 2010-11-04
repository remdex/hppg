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
	   	
	       $photoDir = 'albums/userpics/'.$fileSession->user_id;
	       if (!file_exists($photoDir))
	       mkdir($photoDir,$config->conf->getSetting( 'site', 'StorageDirPermissions' ));
	       
	       
	       $photoDir = 'albums/userpics/'.$fileSession->user_id.'/'.$fileSession->album_id;
	       if (!file_exists($photoDir))
	       mkdir($photoDir,$config->conf->getSetting( 'site', 'StorageDirPermissions' ));	       
	       
	       $fileNamePhysic = erLhcoreClassImageConverter::sanitizeFileName($_FILES['Filedata']['name']);
	       
	       if (file_exists($photoDir.'/'.$fileNamePhysic)) {
	       		$fileNamePhysic = erLhcoreClassModelForgotPassword::randomPassword(5).time().'-'.$fileNamePhysic;
	       }
	       
	       $filetype->process($image,array(
    	       'photo_dir'        => $photoDir,
    	       'file_name_physic' => $fileNamePhysic,
    	       'post_file_name'   => 'Filedata',
    	       'file_session'     => $fileSession
	       ));
	       
	       /*erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumbbig', $_FILES['Filedata']['tmp_name'], $photoDir.'/normal_'.$fileNamePhysic ); 
	       erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumb', $_FILES['Filedata']['tmp_name'], $photoDir.'/thumb_'.$fileNamePhysic ); 
	       	       
	       chmod($photoDir.'/normal_'.$fileNamePhysic,$config->conf->getSetting( 'site', 'StorageFilePermissions' ));
	       chmod($photoDir.'/thumb_'.$fileNamePhysic,$config->conf->getSetting( 'site', 'StorageFilePermissions' ));
	       
	       $dataWatermark = erLhcoreClassModelSystemConfig::fetch('watermark_data')->data;	       
	       // If watermark have to be applied we use conversion othwrwise just upload original to avoid any quality loose.
	       if ($dataWatermark['watermark_disabled'] == false && $dataWatermark['watermark_enabled_all'] == true) {	       	
            	erLhcoreClassImageConverter::getInstance()->converter->transform( 'jpeg', $_FILES['Filedata']['tmp_name'], $photoDir.'/'.$fileNamePhysic ); 
           } else  {
	       		move_uploaded_file($_FILES["Filedata"]["tmp_name"],$photoDir.'/'.$fileNamePhysic);
           }
           
           chmod($photoDir.'/'.$fileNamePhysic,$config->conf->getSetting( 'site', 'StorageFilePermissions' ));
	       
	       $image->filesize = filesize($photoDir.'/'.$fileNamePhysic);
	       $image->total_filesize = filesize($photoDir.'/'.$fileNamePhysic)+filesize($photoDir.'/thumb_'.$fileNamePhysic)+filesize($photoDir.'/normal_'.$fileNamePhysic);
	       $image->filepath = 'userpics/'.$fileSession->user_id.'/'.$fileSession->album_id.'/';
	       
	       $imageAnalyze = new ezcImageAnalyzer( $photoDir.'/'.$fileNamePhysic ); 	       
	       $image->pwidth = $imageAnalyze->data->width;
	       $image->pheight = $imageAnalyze->data->height;*/
	          
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
	       $image->filename = $fileNamePhysic;
	              
	       $session->update($image);
	       $image->clearCache();
           
	              
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