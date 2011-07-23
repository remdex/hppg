<?php

try {
    $Album = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryAlbum', (int)$Params['user_parameters']['album_id'] );
} catch (Exception $e){
    erLhcoreClassModule::redirect('/');
    exit;
}

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/showalbuminfo.tpl.php');
$tpl->set('album',$Album);

echo json_encode(array('result' =>$tpl->fetch()));
exit;