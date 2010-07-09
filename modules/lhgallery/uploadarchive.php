<?php

$sessionID = $_POST['sessionupload'];

$session = erLhcoreClassGallery::getSession();

$q = $session->createFindQuery( 'erLhcoreClassModelGalleryUpload' );
$q->where( $q->expr->eq( 'hash', $q->bindValue( $sessionID ) ) )->limit( 1 );
$objects = $session->find( $q, 'erLhcoreClassModelGalleryUpload' ); 

if (count($objects) == 1)
{	
		
	if (isset($_FILES["Filedata"]) && is_uploaded_file($_FILES["Filedata"]["tmp_name"]) && $_FILES["Filedata"]["error"] == 0 && erLhcoreClassGalleryArchive::isSupportedArchive('Filedata'))
	{	    
	    $fileSession = array_pop($objects);	    
	    $archive = new erLhcoreClassModelGalleryUploadArchive();	   	
	  	    	    	        
	   try {	
	   	    
	       $fileNamePhysic = erLhcoreClassModelForgotPassword::randomPassword(40);	       	       
	       move_uploaded_file($_FILES["Filedata"]["tmp_name"],'var/archives/'.$fileNamePhysic.'.zip');	
		   $config = erConfigClassLhConfig::getInstance();
		   chmod('var/archives/'.$fileNamePhysic.'.zip',$config->conf->getSetting( 'site', 'StorageFilePermissions' ));
	           	  
	       $archive->filename = $fileNamePhysic.'.zip';	 	       
	       $archive->album_name = (isset($_POST['title']) && $_POST['title'] != '') ? $_POST['title'] : '';
	       $archive->album_id = (isset($_POST['album_id']) && $_POST['album_id'] > 0) ? $_POST['album_id'] : 0;
	       $archive->description = (isset($_POST['description']) && $_POST['description'] != '') ? $_POST['description'] : '';
	       $archive->keywords =  (isset($_POST['keyword']) && $_POST['keyword'] != '') ? $_POST['keyword'] : '';	
	       $archive->user_id =  $fileSession->user_id;	
	       	            
	       $session->save($archive);  
	       
	    } catch (Exception $e) {
	        $session->delete($archive);
	        erLhcoreClassLog::write('Exception during archive upload'.$e);
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