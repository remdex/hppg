<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhsystem/cachestatus.tpl.php');

$cache = CSCacheAPC::getMem();


$tpl->set('last_hits_version',$cache->getCacheVersion('last_hits_version',time(),600));
$tpl->set('top_rated',$cache->getCacheVersion('top_rated'));
$tpl->set('last_commented',$cache->getCacheVersion('last_commented'));
$tpl->set('most_popular_version',$cache->getCacheVersion('most_popular_version',time(),1500));
$tpl->set('popularrecent_version',$cache->getCacheVersion('popularrecent_version',time(),600));


$Result['content'] = $tpl->fetch();
$Result['path'] = array(array('url' => erLhcoreClassDesign::baseurl('system/configuration'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('system/configuration','Cache status')));

?>