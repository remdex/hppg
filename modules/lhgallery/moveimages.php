<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/moveimages.tpl.php');
$AlbumData = $Params['user_object'];


if (isset($_POST['MoveImages']) && isset($_POST['AlbumDestinationDirectory0']) && is_numeric($_POST['AlbumDestinationDirectory0'])) {
    
    $newImagesAlbum = erLhcoreClassModelGalleryAlbum::fetch($_POST['AlbumDestinationDirectory0']);
    
    // Assign new album
    $db = ezcDbInstance::get();
    $q = $db->createUpdateQuery();
    $q->update( 'lh_gallery_images' )
      ->set( 'aid', $q->bindValue( $newImagesAlbum->aid ) )
      ->where( $q->expr->eq( 'aid', $q->bindValue( $AlbumData->aid ) ) );
    $stmt = $q->prepare();
    $stmt->execute();
    CSCacheAPC::getMem()->increaseImageManipulationCache();
    
    $AlbumData->clearAlbumCache();
    $newImagesAlbum->clearAlbumCache();
        	       
    $tpl->set('assigned',true);
    $tpl->set('new_album',$newImagesAlbum);
}


$tpl->set('album',$AlbumData);
$Result['content'] = $tpl->fetch();
$pathObjects = array();
$pathCategorys = array();
erLhcoreClassModelGalleryCategory::calculatePathObjects($pathObjects,$AlbumData->category);        
foreach ($pathObjects as $pathItem)
{
   $path[] = array('url' => erLhcoreClassDesign::baseurl('gallery/admincategorys').'/'.$pathItem->cid,'title' => $pathItem->name); 
   $pathCategorys[] = $pathItem->cid; 
}

$path[] = array('url' => erLhcoreClassDesign::baseurl('gallery/managealbumimages').'/'.$AlbumData->aid,'title' => $AlbumData->title);

$Result['path'] = $path;
$Result['path_cid'] = $pathCategorys;
$Result['album_id'] = $AlbumData->aid;