<?php

$session = erLhcoreClassGallery::getSession();
$q = $session->createFindQuery( 'erLhcoreClassModelGalleryUpload' );
$q->where( $q->expr->eq( 'hash', $q->bindValue( $Params['user_parameters']['hash'] ) ) )->limit( 1 );
$objects = $session->find( $q, 'erLhcoreClassModelGalleryUpload' ); 
if (count($objects) == 1)
{
    $fileSession = array_pop($objects);
    $session->delete($fileSession);
    
    CSCacheAPC::getMem()->increaseImageManipulationCache();
    
    // Expires last uploads shard index
	erLhcoreClassGallery::expireShardIndexByIdentifier(array('last_uploads'));
}
exit;

?>