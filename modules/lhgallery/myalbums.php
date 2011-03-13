<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/myalbums.tpl.php');

$currentUser = erLhcoreClassUser::instance();

if (isset($_POST['UpdatePriorityAlbum'])) {    
    foreach ($_POST['AlbumIDs'] as $key => $albumID) {         
       if (($album = erLhcoreClassModelGalleryAlbum::isAlbumOwner($albumID)) !== false && is_numeric($_POST['Position'][$key])){        
            $album->pos = $_POST['Position'][$key];
            erLhcoreClassGallery::getSession()->update( $album );            
            $album->clearAlbumCache();            
       }
    }
}

$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelGalleryAlbum::getAlbumCount(array('disable_sql_cache' => true, 'filter' => array('owner_id' => $currentUser->getUserID())));
$pages->serverURL = erLhcoreClassDesign::baseurl('gallery/myalbums');
$pages->paginate();

$tpl->set('pages',$pages);
$tpl->set('owner_id',$currentUser->getUserID());

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('user/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/myalbums','Account')),

array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/myalbums','My albums'))


);