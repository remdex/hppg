<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/moveimage.tpl.php');
$image = $Params['user_object'];


if (isset($_POST['MoveImages']) && isset($_POST['AlbumDestinationDirectory0']) && is_numeric($_POST['AlbumDestinationDirectory0'])) {
    
    $newImagesAlbum = erLhcoreClassModelGalleryAlbum::isAlbumOwner($_POST['AlbumDestinationDirectory0'],erLhcoreClassUser::instance()->hasAccessTo('lhgallery','administrate'));        
    if ($newImagesAlbum instanceof erLhcoreClassModelGalleryAlbum) {
        // Assign new album
        $db = ezcDbInstance::get();
        $q = $db->createUpdateQuery();
        $q->update( 'lh_gallery_images' )
          ->set( 'aid', $q->bindValue( $newImagesAlbum->aid ) )
          ->where( $q->expr->eq( 'pid', $q->bindValue( $image->pid ) ) );
        $stmt = $q->prepare();
        $stmt->execute();
        CSCacheAPC::getMem()->increaseImageManipulationCache();
        
        $image->album->clearAlbumCache();
        $newImagesAlbum->clearAlbumCache();
            
        $tpl->set('assigned',true);
    }
}


$tpl->set('image',$image);
$Result['content'] = $tpl->fetch();
$Result['pagelayout'] = 'popup';