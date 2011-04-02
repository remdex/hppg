<?php

$tpl = erLhcoreClassTemplate::getInstance('lhgallery/switchimage.tpl.php');

$Image = $Params['user_object'];

if (isset($_POST['UploadPhoto'])) {
    
    if (isset($_FILES["Filedata"]) && is_uploaded_file($_FILES["Filedata"]["tmp_name"]) && $_FILES["Filedata"]["error"] == 0)
	{
	    $Errors = array();
	    
	    if (($filetype = erLhcoreClassModelGalleryFiletype::isValid('Filedata')) !== false){
	        
	        $session = erLhcoreClassGallery::getSession();

	        $photoDir = 'albums/'.$Image->filepath;

	        $fileNamePhysic = erLhcoreClassImageConverter::sanitizeFileName($_FILES['Filedata']['name']);	       
	        if (file_exists($photoDir.'/'.$fileNamePhysic)) {
	       		$fileNamePhysic = erLhcoreClassModelForgotPassword::randomPassword(5).time().'-'.$fileNamePhysic;
	        }

	        $Image->removeFiles();

	        $filetype->process($Image,array(
    	       'photo_dir'        => $photoDir,
    	       'photo_dir_photo'  => $Image->filepath,
    	       'file_name_physic' => $fileNamePhysic,
    	       'post_file_name'   => 'Filedata',
    	       'file_session'     => array()
	        ));

	        $Image->filename = $fileNamePhysic;
	        
	        // Index colors
	        erLhcoreClassPalleteIndexImage::indexImage($Image);
           
	        // Index face if needed
	        erLhcoreClassModelGalleryFaceData::indexImage($Image);
	       
	        // Index in search table
	        erLhcoreClassModelGallerySphinxSearch::indexImage($Image);
 
	        $session->update($Image);
	        
	    } else {
	        $Errors[] = 'Not supported file type';
	    }
	    
	    if (count($Errors) == 0){
	        $tpl->set('image_replace',true);
	    } else {
	        $tpl->set('errors',$Errors);
	    }
	}
}

$tpl->set('image',$Image);
$Result['content'] = $tpl->fetch();
$Result['pagelayout'] = 'popup';