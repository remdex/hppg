<?php

//*/5 * * * * cd /home/www/domains/hentai_wallpapers_com && /usr/bin/php cron.php -l eng  > image_counter.log /dev/null 2>&1
erLhcoreClassModelGalleryDelayImageHit::updateMainCounter();

$session = erLhcoreClassGallery::getSession();
$q = $session->createFindQuery( 'erLhcoreClassModelGalleryUploadArchive' );  
$objects = $session->find( $q, 'erLhcoreClassModelGalleryUploadArchive' );


foreach ($objects as $object)
{
	$object->import();
}

if (count($objects) > 0)
CSCacheAPC::getMem()->increaseImageManipulationCache();

echo "Gallery cron";

echo "Update finished ",date('Y-m-d :H:i'),", Images updated - ","\n";
exit;