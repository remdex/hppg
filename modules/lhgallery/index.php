<?php
$cache = CSCacheAPC::getMem(); 
$cacheKey =   md5('index_page'.'_siteaccess_'.erLhcoreClassSystem::instance()->SiteAccess.'_version_'.$cache->getCacheVersion('site_version').'_last_commented_v_'.$cache->getCacheVersion('last_commented').'_last_rated_v_'.$cache->getCacheVersion('last_rated').'_ratedrecent_v_'.$cache->getCacheVersion('ratedrecent_version').'_popularrecent_v_'.$cache->getCacheVersion('popularrecent_version',time(),600));

if (($Result = $cache->restore($cacheKey)) === false)
{ 
    $tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/index.tpl.php');
    $Result['content'] = $tpl->fetch();
    $Result['path'] = array();   
    $cache->store($cacheKey,$Result);
}

?>