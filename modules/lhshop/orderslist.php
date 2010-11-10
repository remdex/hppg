<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhshop/orderslist.tpl.php');


$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelShopOrder::getListCount(array('filterin' => array('status' => array(1,2))));
$pages->serverURL = erLhcoreClassDesign::baseurl('shop/orderlist');
$pages->paginate();

$tpl->set('pages',$pages);

$Result['content'] = $tpl->fetch();
$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('shop/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/index','Shop')),
array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderslist','Orders list'))

);

?>