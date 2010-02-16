<?php
  
$cache = CSCacheAPC::getMem();
$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/managealbumimages.tpl.php');
$Album = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryAlbum', (int)$Params['user_parameters']['album_id'] );    
$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelGalleryImage::getImageCount(array('cache_key' => 'albumlist_'.$cache->getCacheVersion('album_'.$Album->aid),'filter' => array('aid' => $Album->aid)));
$pages->translationContext = 'gallery/album';
$pages->serverURL = erLhcoreClassDesign::baseurl('gallery/managealbumimages/').$Album->aid;
$pages->paginate();

$tpl->set('pages',$pages);
$tpl->set('album',$Album);

$Result['content'] = $tpl->fetch();

$path = array();
$path[] = array('url' => erLhcoreClassDesign::baseurl('/gallery/admincategorys/'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/managealbumimages','Home')); 
$pathObjects = array();
erLhcoreClassModelGalleryCategory::calculatePathObjects($pathObjects,$Album->category);        
foreach ($pathObjects as $pathItem)
{
   $path[] = array('url' => erLhcoreClassDesign::baseurl('/gallery/admincategorys/').$pathItem->cid,'title' => $pathItem->name); 
}

$Result['path'] = $path;


?>