<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/managealbum.tpl.php');
$Category = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryCategory', (int)$Params['user_parameters']['category_id'] );

if (isset($_POST['UpdatePriorityAlbum'])) {
    
    foreach ($_POST['AlbumIDs'] as $key => $albumID) {
        $album = erLhcoreClassModelGalleryAlbum::fetch($albumID);
        $album->pos = $_POST['Position'][$key];
        erLhcoreClassGallery::getSession()->update( $album );
    }
}

$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelGalleryAlbum::getAlbumCount(array('disable_sql_cache' => true,'filter' => array('category' => $Category->cid)));
$pages->setItemsPerPage(8);
$pages->serverURL = erLhcoreClassDesign::baseurl('gallery/managealbum').'/'.$Category->cid;
$pages->paginate();

$tpl->set('pages',$pages);
$tpl->set('category',$Category);

$Result['content'] = $tpl->fetch();
$path = array();

$pathObjects = array();
erLhcoreClassModelGalleryCategory::calculatePathObjects($pathObjects,$Category->cid);        
foreach ($pathObjects as $pathItem)
{
   $path[] = array('url' => erLhcoreClassDesign::baseurl('gallery/admincategorys').'/'.$pathItem->cid,'title' => $pathItem->name); 
}


$Result['path'] = $path;

?>