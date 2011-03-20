<?php
  
$Album = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryAlbum', (int)$Params['user_parameters']['album_id'] ); 
$cache = CSCacheAPC::getMem();
$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/movebyresolution.tpl.php');

if (isset($_POST['moveSelectedPhotos']) && is_numeric($_POST['AlbumDestinationDirectory0'])){

    list($width,$height) = explode('x',$_POST['ResolutionSource']);           
    $albumDestination = erLhcoreClassModelGalleryAlbum::fetch($_POST['AlbumDestinationDirectory0']);

    $db = ezcDbInstance::get();
    $q = $db->createUpdateQuery();
    $q->update( 'lh_gallery_images' )
      ->set( 'aid', $albumDestination->aid )
      ->where( $q->expr->eq( 'pwidth', (int)$width ),
               $q->expr->eq( 'pheight', (int)$height ),
               $q->expr->eq( 'aid', $Album->aid ) 
                );
    $stmt = $q->prepare();
    $stmt->execute();
     
    $albumDestination->clearAlbumCache();
    $Album->clearAlbumCache();
    
    $tpl->set('effected_images',$stmt->rowCount());
    $tpl->set('filter_resolution',$_POST['ResolutionSource']);
}
   
$tpl->set('album',$Album);

$Result['content'] = $tpl->fetch();

$path = array();
$path[] = array('url' => erLhcoreClassDesign::baseurl('gallery/admincategorys'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/managealbumimages','Root category')); 
$pathObjects = array();
erLhcoreClassModelGalleryCategory::calculatePathObjects($pathObjects,$Album->category);   
 
$pathCategorys = array();      
foreach ($pathObjects as $pathItem)
{
   $path[] = array('url' => erLhcoreClassDesign::baseurl('gallery/admincategorys').'/'.$pathItem->cid,'title' => $pathItem->name);
   $pathCategorys[] = $pathItem->cid; 
}

$path[] = array('url' => erLhcoreClassDesign::baseurl('gallery/managealbumimages').'/'.$Album->aid,'title' => $Album->title);
$path[] = array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/movebyresolution','Move by resolution'));

$Result['path'] = $path;
$Result['path_cid'] = $pathCategorys;
$Result['album_id'] = $Album->aid;