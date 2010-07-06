<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhshop/myorders.tpl.php');

$currentUser = erLhcoreClassUser::instance();
$tpl->set('orders',erLhcoreClassModelShopOrder::getList(array('filterin' => array('status' => array(1,2)),'filter' => array('user_id' => $currentUser->getUserID()))));


$Result['content'] = $tpl->fetch();
$Result['path'] = array(array('url' => erLhcoreClassDesign::baseurl('shop/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/index','Shop')));

?>