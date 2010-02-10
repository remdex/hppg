<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/myalbums.tpl.php');

$currentUser = erLhcoreClassUser::instance();

$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelGalleryAlbum::getAlbumCount(array('disable_sql_cache' => true, 'filter' => array('owner_id' => $currentUser->getUserID())));
$pages->translationContext = 'gallery/album';
$pages->serverURL = '/gallery/myalbums';
$pages->paginate();

$tpl->set('pages',$pages);
$tpl->set('owner_id',$currentUser->getUserID());
$Result['content'] = $tpl->fetch();

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('user/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','My account')),

array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','My albums'))


);