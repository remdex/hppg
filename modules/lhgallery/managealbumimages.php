<?php
  

if (isset($_POST['moveSelectedPhotos']) && isset($_POST['PhotoID']) && count($_POST['PhotoID']) > 0 && is_numeric($_POST['AlbumDestinationDirectory0'])){
    foreach ($_POST['PhotoID'] as $photoID) {        
        $image = erLhcoreClassModelGalleryImage::fetch($photoID);

        $album = erLhcoreClassModelGalleryAlbum::fetch($image->aid);
        $album->clearAlbumCache();

        $image->aid = $_POST['AlbumDestinationDirectory0'];
        erLhcoreClassGallery::getSession()->update($image); 
        $image->clearCache();

        erLhcoreClassModelGallerySphinxSearch::indexImage($image);
    }
}

// Batch images approvement and disapprovement
if (isset($Params['user_parameters_unordered']['action']) && $Params['user_parameters_unordered']['action'] != '') {
    
    $db = ezcDbInstance::get();
    $images = erLhcoreClassModelGalleryImage::getImages(array('limit' => 5000,'filter' => array('aid' => (int)$Params['user_parameters']['album_id'],'approved' => $Params['user_parameters_unordered']['action'] == 'approve' ? 0 : 1)));
    foreach ($images as $image) {
        if ($Params['user_parameters_unordered']['action'] == 'approve') {            
            $image->approved = 1;
        } else {
            $image->approved = 0;
        }
               
        erLhcoreClassModelGallerySphinxSearch::indexImage($image);
        erLhcoreClassGallery::getSession()->update($image);        
    }
    
    $album = erLhcoreClassModelGalleryAlbum::fetch((int)$Params['user_parameters']['album_id']);
    $album->clearAlbumCache();
}


$cache = CSCacheAPC::getMem();
$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/managealbumimages.tpl.php');
$Album = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryAlbum', (int)$Params['user_parameters']['album_id'] );    
$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelGalleryImage::getImageCount(array('cache_key' => 'albumlist_'.$cache->getCacheVersion('album_'.$Album->aid),'filter' => array('aid' => $Album->aid)));
$pages->serverURL = erLhcoreClassDesign::baseurl('gallery/managealbumimages').'/'.$Album->aid;
$pages->paginate();

$tpl->set('pages',$pages);
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


$Result['path'] = $path;
$Result['path_cid'] = $pathCategorys;
$Result['album_id'] = $Album->aid;