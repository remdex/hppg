<?php

$sessionID = $_POST['sessionupload'];

$session = erLhcoreClassGallery::getSession();

$q = $session->createFindQuery( 'erLhcoreClassModelGalleryUpload' );
$q->where( $q->expr->eq( 'hash', $q->bindValue( $sessionID ) ) )->limit( 1 );
$objects = $session->find( $q, 'erLhcoreClassModelGalleryUpload' ); 

if (count($objects) == 1)
{	
	if (isset($_FILES["Filedata"]) && is_uploaded_file($_FILES["Filedata"]["tmp_name"]) && $_FILES["Filedata"]["error"] == 0 && erLhcoreClassImageConverter::isPhoto('Filedata'))
	{
	    
	    $fileSession = array_pop($objects);
	    
	    $image = new erLhcoreClassModelGalleryImage();
	    $image->aid = $fileSession->album_id;	
	    $session->save($image);
	    	        
	   try {
	      
	       $photoDir = 'albums/userpics/'.$fileSession->user_id;
	       if (!file_exists($photoDir))
	       mkdir($photoDir,0777);
	       
	       $photoDir = 'albums/userpics/'.$fileSession->user_id.'/'.$fileSession->album_id;
	       if (!file_exists($photoDir))
	       mkdir($photoDir,0777);	       
	       
	       erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumbbig', $_FILES['Filedata']['tmp_name'], $photoDir.'/normal_'.erLhcoreClassImageConverter::sanitizeFileName($_FILES['Filedata']['name']) ); 
	       erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumb', $_FILES['Filedata']['tmp_name'], $photoDir.'/thumb_'.erLhcoreClassImageConverter::sanitizeFileName($_FILES['Filedata']['name']) ); 
	       move_uploaded_file($_FILES["Filedata"]["tmp_name"],$photoDir.'/'.erLhcoreClassImageConverter::sanitizeFileName($_FILES['Filedata']['name']));
	       
	       $image->filesize = filesize($photoDir.'/'.erLhcoreClassImageConverter::sanitizeFileName($_FILES['Filedata']['name']));
	       $image->total_filesize = filesize($photoDir.'/'.erLhcoreClassImageConverter::sanitizeFileName($_FILES['Filedata']['name']))+filesize($photoDir.'/thumb_'.erLhcoreClassImageConverter::sanitizeFileName($_FILES['Filedata']['name']))+filesize($photoDir.'/normal_'.erLhcoreClassImageConverter::sanitizeFileName($_FILES['Filedata']['name']));
	       $image->filepath = 'userpics/'.$fileSession->user_id.'/'.$fileSession->album_id.'/';
	       
	       $imageAnalyze = new ezcImageAnalyzer( $photoDir.'/'.erLhcoreClassImageConverter::sanitizeFileName($_FILES['Filedata']['name']) ); 	       
	       $image->pwidth = $imageAnalyze->data->width;
	       $image->pheight = $imageAnalyze->data->height;
	       $image->hits = 0;
	       $image->ctime = time();
	       $image->owner_id = $fileSession->user_id;
	       $image->pic_rating = 0;
	       $image->votes = 0;
	       
	       $image->title = $_POST['title'];
	       $image->caption = $_POST['description'];
	       $image->keywords =  $_POST['keyword'];
	       $image->approved =  1;
	       $image->filename = erLhcoreClassImageConverter::sanitizeFileName($_FILES['Filedata']['name']);
	              
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

//echo "wrong";
exit;

?>