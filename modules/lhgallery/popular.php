<?php

// Expire all list at once.
$cache = CSCacheAPC::getMem();   
$cacheVersion = $cache->getCacheVersion('most_popular_version',time(),1500);

if (($Result = $cache->restore(md5('version_'.$cacheVersion.'popular_view_url'.'_page_'.$Params['user_parameters_unordered']['page'].'_siteaccess_'.erLhcoreClassSystem::instance()->SiteAccess))) === false)
{ 
    $tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/popular.tpl.php');
    $pages = new lhPaginator();
    $pages->items_total = erLhcoreClassModelGalleryImage::getImageCount(array('disable_sql_cache' => true));
    $pages->translationContext = 'gallery/album';
    $pages->serverURL = erLhcoreClassDesign::baseurl('/gallery/popular');
    $pages->paginate();
    
    $tpl->set('pages',$pages);
    $Result['content'] = $tpl->fetch();
    $Result['path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/popular','Most popular images')));    
    $Result['rss']['title'] = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/popular','Most popular images');
    $Result['rss']['url'] = erLhcoreClassDesign::baseurl('/gallery/popularrss/');

    $cache->store(md5('version_'.$cacheVersion.'popular_view_url'.'_page_'.$Params['user_parameters_unordered']['page']),$Result);
}

?>