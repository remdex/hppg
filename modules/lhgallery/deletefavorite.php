<?php

try {
$Image = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryImage', (int)$Params['user_parameters']['image_id'] );
} catch (Exception $e){
	erLhcoreClassModule::redirect();
    exit;
}

$favouriteSession = erLhcoreClassModelGalleryMyfavoritesSession::getInstance();

if (erLhcoreClassModelGalleryMyfavoritesImage::getImageCount(array('filter' => array('pid' => $Image->pid,'session_id'=> $favouriteSession->id))) == 1)
{	
	$objects = erLhcoreClassModelGalleryMyfavoritesImage::getImages(array('filter' => array('pid' => $Image->pid,'session_id'=> $favouriteSession->id)));
		
	foreach ($objects as $object)
	{
		$object->removeThis();
	}	
}

echo json_encode(array('result' => 'ok'));
exit;

?>