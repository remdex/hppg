<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/duplicates.tpl.php');


$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelGalleryDuplicateCollection::getDuplicatesCount();
$pages->serverURL = erLhcoreClassDesign::baseurl('gallery/duplicates');
$pages->paginate();



$tpl->set('pages',$pages);

if ($pages->items_total > 0) {
	$tpl->set('duplicates',erLhcoreClassModelGalleryDuplicateCollection::getDuplicates(array('offset' => $pages->low, 'limit' => $pages->items_per_page)));
}

$Result['content'] = $tpl->fetch();
$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('gallery/duplicates'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/duplicates','Duplicates'))
);