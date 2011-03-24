<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/ratebanlist.tpl.php');


if (!empty($Params['user_parameters_unordered']['delete']) && is_numeric($Params['user_parameters_unordered']['delete'])){
    try {
        erLhcoreClassModelGalleryRateBanIP::fetch($Params['user_parameters_unordered']['delete'])->removeThis();
    } catch (Exception $e) {
        
    }
}

if (isset($_POST['AddIP'])) {    
    if ($_POST['IP'] != '')
    {
        $ban = new erLhcoreClassModelGalleryRateBanIP();
        $ban->ip = $_POST['IP'];
        $ban->saveThis();
    }
}

$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelGalleryRateBanIP::getCount();
$pages->setItemsPerPage(20);
$pages->serverURL = erLhcoreClassDesign::baseurl('gallery/ratebanlist');
$pages->paginate();

$tpl->set('pages',$pages);

if ($pages->items_total > 0) {
    $tpl->set('items',erLhcoreClassModelGalleryRateBanIP::getList(array('offset' => $pages->low, 'limit' => $pages->items_per_page )));
} else {
    $tpl->set('items',array());
}

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('system/configuration'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/ratebanlist','System configuration')),

array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/ratebanlist','Rate ban IP'))
);