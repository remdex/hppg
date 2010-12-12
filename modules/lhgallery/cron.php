<?php

erLhcoreClassModelGalleryDelayImageHit::updateMainCounter();

erLhcoreClassModelGalleryPopular24::deleteExpired();
erLhcoreClassModelGalleryRated24::deleteExpired();

$session = erLhcoreClassGallery::getSession();
$q = $session->createFindQuery( 'erLhcoreClassModelGalleryUploadArchive' );  
$q->where( $q->expr->eq( 'status', $q->bindValue( 0 ) ) )
  ->limit(2); // Limit import to max 2 archives at once
$objects = $session->find( $q, 'erLhcoreClassModelGalleryUploadArchive' );

foreach ($objects as $object)
{
	$object->import();
}

if (count($objects) > 0)
CSCacheAPC::getMem()->increaseImageManipulationCache();

echo "Cron finished - ",date('Y-m-d :H:i'),"\n";
exit;