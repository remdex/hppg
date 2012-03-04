<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/unconfirmedimages.tpl.php');



if (isset($_POST['approveSelected']) && isset($_POST['PhotoID']) && count($_POST['PhotoID']) > 0){
    foreach ($_POST['PhotoID'] as $photoID) {   
             
        $image = erLhcoreClassModelGalleryImage::fetch($photoID);   
        $image->approved = 1;
            
        erLhcoreClassGallery::getSession()->update($image); 
        
        $image->clearCache();

        erLhcoreClassPalleteIndexImage::indexImage($image);  // We do not use delay, because it's update 
        erLhcoreClassModelGalleryImgSeekData::indexImage($image); 
        erLhcoreClassModelGalleryAlbum::updateAddTime($image);
    }

    CSCacheAPC::getMem()->increaseCacheVersion('color_images');

    CSCacheAPC::getMem()->increaseImageManipulationCache();

    // Expires last uploads shard index
    erLhcoreClassGallery::expireShardIndexByIdentifier(array('last_uploads','last_commented'));
    
    $tpl->set('approved_count', count($_POST['PhotoID']));
}


if (isset($_POST['removeSelected']) && isset($_POST['PhotoID']) && count($_POST['PhotoID']) > 0){
    foreach ($_POST['PhotoID'] as $photoID) {
        $image = erLhcoreClassModelGalleryImage::fetch($photoID);   
        $image->removeThis();
    }
    
    $tpl->set('remove_count', count($_POST['PhotoID']));
}


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


$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelGalleryImage::getImageCount(array('filter' => array('approved' => 0)));
$pages->serverURL = erLhcoreClassDesign::baseurl('gallery/unconfirmedimages');
$pages->paginate();

$tpl->set('pages',$pages);

$Result['content'] = $tpl->fetch();
$Result['path_base'] = erLhcoreClassDesign::baseurldirect('gallery/unconfirmedimages').($pages->current_page > 1 ? '/(page)/'.$pages->current_page : ''); 

$Result['path'] = array(); 
$Result['path'][] = array('url' =>erLhcoreClassDesign::baseurl('gallery/unconfirmedimages'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/lastuploads','Unconfirmed images'));    

if ($Params['user_parameters_unordered']['page'] > 1) {        
    $Result['path'][] = array('url' => erLhcoreClassDesign::baseurl('gallery/unconfirmedimages').'/(page)/'.$Params['user_parameters_unordered']['page'], 'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/lastuploads','Page').' - '.(int)$Params['user_parameters_unordered']['page']); 
}

    
?>