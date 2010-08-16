<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/lastsearches.tpl.php');

$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelGallerySearchHistory::getSearchCount();
$pages->translationContext = 'gallery/album';
$pages->serverURL = erLhcoreClassDesign::baseurl('/gallery/lastsearches');
$pages->paginate();

$tpl->set('pages',$pages);

$Result['content'] = $tpl->fetch();
   

?>