<?php

// Expire all list at once.
$cache = CSCacheAPC::getMem(); 

$resolutions = erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'resolutions' ); 
$resolution = isset($Params['user_parameters_unordered']['resolution']) && key_exists($Params['user_parameters_unordered']['resolution'],$resolutions) ? $Params['user_parameters_unordered']['resolution'] : '';

$appendResolutionMode = $resolution != '' ? '/(resolution)/'.$resolution : '';
$filterArray = array();

if ($resolution != ''){
    $filterArray['pwidth'] = $resolutions[$resolution]['width'];
    $filterArray['pheight'] = $resolutions[$resolution]['height'];
}

$cacheVersion = $cache->getCacheVersion('most_popular_version',time(),1500);

if (($Result = $cache->restore(md5('version_'.$cacheVersion.'popular_view_url'.$resolution.'_page_'.$Params['user_parameters_unordered']['page'].'_siteaccess_'.erLhcoreClassSystem::instance()->SiteAccess))) === false)
{ 
    $tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/popular.tpl.php');
    $pages = new lhPaginator();
    $pages->items_total = erLhcoreClassModelGalleryImage::getImageCount(array('disable_sql_cache' => true,'filter' => $filterArray));
    $pages->translationContext = 'gallery/popular';
    $pages->serverURL = erLhcoreClassDesign::baseurl('/gallery/popular').$appendResolutionMode;
    $pages->paginate();
    
    $tpl->set('pages',$pages);
    $tpl->set('currentResolution',$resolution);
    $tpl->set('filterArray',$filterArray);
    
    $appendImageMode = '/(mode)/popular'.$appendResolutionMode;
       
    $tpl->set('appendImageMode',$appendImageMode);
    $tpl->set('urlSortBase',erLhcoreClassDesign::baseurl('/gallery/popular'));
        
    $Result['content'] = $tpl->fetch();
    $Result['path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/popular','Most popular images')));    
    $Result['rss']['title'] = erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/popular','Most popular images');
    $Result['rss']['url'] = erLhcoreClassDesign::baseurl('/gallery/popularrss/');

    $cache->store(md5('version_'.$cacheVersion.'popular_view_url'.'_page_'.$Params['user_parameters_unordered']['page']),$Result);
}

?>