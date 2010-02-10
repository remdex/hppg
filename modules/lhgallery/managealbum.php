<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/managealbum.tpl.php');
$Category = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryCategory', (int)$Params['user_parameters']['category_id'] );

$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelGalleryAlbum::getAlbumCount(array('filter' => array('category' => $Category->cid)));
$pages->translationContext = 'gallery/album';
$pages->default_ipp = 8;
$pages->serverURL = erLhcoreClassDesign::baseurl('/gallery/managealbum/').$Category->cid;
$pages->paginate();

$tpl->set('pages',$pages);
$tpl->set('category',$Category);

$Result['content'] = $tpl->fetch();
$path = array();
erLhcoreClassModelGalleryCategory::getCategoryPath($path,$Category->cid);
$Result['path'] = $path;

?>