<?php

erLhcoreClassModelGalleryDelayImageHit::updateMainCounter();

erLhcoreClassModelGalleryPopular24::deleteExpired();
erLhcoreClassModelGalleryRated24::deleteExpired();

$session = erLhcoreClassGallery::getSession();
$q = $session->createFindQuery( 'erLhcoreClassModelGalleryUploadArchive' );  
$objects = $session->find( $q, 'erLhcoreClassModelGalleryUploadArchive' );

foreach ($objects as $object)
{
	$object->import();
}

if (count($objects) > 0)
CSCacheAPC::getMem()->increaseImageManipulationCache();

echo "Cron finished - ",date('Y-m-d :H:i'),"\n";
exit;