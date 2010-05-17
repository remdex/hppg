<?php

try {
$Image = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryImage', (int)$Params['user_parameters']['image_id'] );
} catch (Exception $e){
	erLhcoreClassModule::redirect();
    exit;
}

$favouriteSession = erLhcoreClassModelGalleryMyfavoritesSession::getInstance();

if (erLhcoreClassModelGalleryMyfavoritesImage::getImageCount(array('filter' => array('pid' => $Image->pid,'session_id'=> $favouriteSession->id))) == 0)
{
	$imageFavourite = new erLhcoreClassModelGalleryMyfavoritesImage();
	$imageFavourite->session_id = $favouriteSession->id;
	$imageFavourite->pid = $Image->pid;
	
	erLhcoreClassGallery::getSession()->save($imageFavourite);
	$favouriteSession->clearFavoriteCache();
}

echo json_encode(array('result' => 'ok'));
exit;

?>