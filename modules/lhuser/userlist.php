<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhuser/userlist.tpl.php');

$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelUser::getUserCount();
$pages->translationContext = 'user/userlist';
$pages->default_ipp = 20;
$pages->serverURL = erLhcoreClassDesign::baseurl('user/userlist');
$pages->paginate();

$tpl->set('pages',$pages);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('system/configuration'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','System configuration')),

array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','Users'))
)
?>