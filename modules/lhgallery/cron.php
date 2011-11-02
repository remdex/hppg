<?php

erLhcoreClassModelGalleryDelayImageHit::updateMainCounter();

erLhcoreClassModelGalleryPopular24::deleteExpired();
erLhcoreClassModelGalleryRated24::deleteExpired();

// Index to color attributes
erLhcoreClassPalleteIndexImage::indexUnindexedImages(erConfigClassLhConfig::getInstance()->conf->getSetting( 'color_search', 'delay_index_portion' ));

// Index face data
erLhcoreClassModelGalleryFaceData::indexUnindexedImages(erConfigClassLhConfig::getInstance()->conf->getSetting( 'face_search', 'delay_index_portion' ));

// Index to sphinx search
erLhcoreClassModelGallerySphinxSearch::indexUnindexedImages(erConfigClassLhConfig::getInstance()->conf->getSetting( 'sphinx', 'delay_index_portion' ));

// Index to imgseek service
erLhcoreClassModelGalleryImgSeekData::indexUnindexedImages(erConfigClassLhConfig::getInstance()->conf->getSetting( 'imgseek', 'delay_index_portion' ));

// Delete legacy search
erLhcoreClassModelGallerySearchHistory::deleteLegacySearch();

$session = erLhcoreClassGallery::getSession();
$q = $session->createFindQuery( 'erLhcoreClassModelGalleryUploadArchive' );  
$q->where( $q->expr->eq( 'status', $q->bindValue( 0 ) ) )
  ->limit(1); // Limit import to max 2 archives at once
$objects = $session->find( $q, 'erLhcoreClassModelGalleryUploadArchive' );

foreach ($objects as $object)
{
	$object->import();
}

if (count($objects) > 0)
CSCacheAPC::getMem()->increaseImageManipulationCache();

echo "Cron finished - ",date('Y-m-d :H:i'),"\n";
exit;