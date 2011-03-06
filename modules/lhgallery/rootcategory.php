<?php
$cache = CSCacheAPC::getMem(); 
$cacheKey =   md5('index_page'.'_siteaccess_'.erLhcoreClassSystem::instance()->SiteAccess.'_version_'.$cache->getCacheVersion('site_version'));

if (($Result = $cache->restore($cacheKey)) === false)
{ 
    $tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/rootcategory.tpl.php');
    $Result['content'] = $tpl->fetch();
    $Result['path'] = array();   
    $cache->store($cacheKey,$Result);
}

?>