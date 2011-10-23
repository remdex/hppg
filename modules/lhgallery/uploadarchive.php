<?php

$session = erLhcoreClassGallery::getSession();
$uploader = new qqFileUploader(array('zip'), erLhcoreClassModelSystemConfig::fetch('max_archive_size')->current_value*1024);
$result = $uploader->handleUpload('var/tmpfiles/');

if (isset($result['success']) && $result['success'] == 'true')
{	
    $db = ezcDbInstance::get();
    $db->beginTransaction();
        
    $result['filepath'] = $uploader->getFilePath();  
    $result['filename'] = $uploader->getFileName();
    $result['filename_user'] = $uploader->getUserFileName();
        
    $archive = new erLhcoreClassModelGalleryUploadArchive();	   	

    if ( $uploader->getParam('album_id') == 0 || ($uploader->getParam('album_id') > 0 && ($albumto = erLhcoreClassModelGalleryAlbum::canUpload($uploader->getParam('album_id'),$currentUser->hasAccessTo('lhgallery','administrate'))) !== false) ){    
       try {
           $currentUser = erLhcoreClassUser::instance();
           if ($currentUser->isLogged())
                $user_id = $currentUser->getUserID();	
           else 
               $user_id = erConfigClassLhConfig::getInstance()->conf->getSetting( 'user_settings', 'anonymous_user_id' );
            
           $fileNamePhysic = erLhcoreClassModelForgotPassword::randomPassword(40);	       	       
           rename($result['filepath'],'var/archives/'.$fileNamePhysic.'.zip');	
           
    	   $config = erConfigClassLhConfig::getInstance();
    	   chmod('var/archives/'.$fileNamePhysic.'.zip',$config->conf->getSetting( 'site', 'StorageFilePermissions' ));
               	  
           $archive->filename = $fileNamePhysic.'.zip';	 	       
           $archive->album_name = ($uploader->getParam('title') != '') ? $uploader->getParam('title') : '';
           $archive->album_id = ($uploader->getParam('album_id') > 0) ? $uploader->getParam('album_id') : 0;
           $archive->description = ($uploader->getParam('description') != '') ? $uploader->getParam('description') : '';
           $archive->keywords =  ($uploader->getParam('keyword') != '') ?$uploader->getParam('keyword') : '';	
           $archive->user_id =  $user_id;	
           	            
           $session->save($archive);  
           
           echo json_encode(array('success' => 'true'));
           
        } catch (Exception $e) {
            $session->delete($archive);
            erLhcoreClassLog::write('Exception during archive upload'.$e);
            return ;
        } 
    }   
    
    $db->commit();
    		
} else {
    erLhcoreClassLog::write('Upload failed: '.print_r($_FILES,true));
}

exit;

?>