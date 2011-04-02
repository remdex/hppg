<?php
$cache = CSCacheAPC::getMem();     

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/mylistalbum.tpl.php');
$Album = $Params['user_object'];  
$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelGalleryImage::getImageCount(array('cache_key' => 'albumlist_'.$cache->getCacheVersion('album_'.$Album->aid),'filter' => array('aid' => $Album->aid)));
$pages->serverURL = erLhcoreClassDesign::baseurl('gallery/mylistalbum').'/'.$Album->aid;
$pages->paginate();

$tpl->set('pages',$pages);
$tpl->set('album',$Album);

$Result['content'] = $tpl->fetch();
$Result['path'] = $Album->path_album;
    

$Result['path'] = array (

array('url' => erLhcoreClassDesign::baseurl('user/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/mylistalbum','Account')),
array('url' => erLhcoreClassDesign::baseurl('gallery/myalbums'), 'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/mylistalbum','Albums')),
array('title' => $Album->title),

);

?>